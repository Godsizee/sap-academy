/**
 * Quiz Engine
 * Kapselt die komplette Quiz-Logik und nutzt SessionStorage, 
 * um den Fortschritt bei einem versehentlichen Reload zu behalten.
 */

export function initQuizEngine() {
    const quizScreen = document.getElementById('quiz-screen');
    if (!quizScreen) return;

    const rawQuizData = window.quizData || [];
    if (rawQuizData.length === 0) return;

    let selectedQuestions = [];
    let incorrectlyAnsweredQuestions = [];
    let currentQuestionIndex = 0;
    let score = 0;
    let draggedItem = null;

    // Einzigartiger Schlüssel pro Quiz (z.B. "quizState_s4f10")
    const searchParams = new URLSearchParams(window.location.search);
    const sessionKey = 'quizState_' + (searchParams.get('id') || 'default');

    // DOM Elements
    const selectionScreen = document.getElementById('chapter-selection-screen');
    const resultScreen = document.getElementById('quiz-result-screen');
    const introHeader = document.getElementById('quiz-intro-header');
    const breadcrumb = document.getElementById('quiz-breadcrumb');
    const form = document.getElementById('chapter-select-form');
    
    const progressBar = document.getElementById('quiz-progress-bar-inner');
    const progressText = document.getElementById('quiz-progress-text');
    const questionText = document.getElementById('question-text');
    const optionsContainer = document.getElementById('options-container');
    const checkBtn = document.getElementById('check-answer-btn');
    const nextBtn = document.getElementById('next-question-btn');
    const feedbackArea = document.getElementById('feedback-area');
    
    const scoreFinal = document.getElementById('score-final');
    const totalFinal = document.getElementById('total-final');
    const percentageFinal = document.getElementById('percentage-final');
    const resultHeadline = document.getElementById('result-headline');
    const resultText = document.getElementById('result-text');
    const restartBtn = document.getElementById('restart-quiz-btn');
    const retryIncorrectBtn = document.getElementById('retry-incorrect-btn');

    // --- STATE MANAGEMENT ---
    function saveState() {
        const state = {
            questions: selectedQuestions,
            incorrects: incorrectlyAnsweredQuestions,
            currentIndex: currentQuestionIndex,
            score: score
        };
        sessionStorage.setItem(sessionKey, JSON.stringify(state));
    }

    function clearState() {
        sessionStorage.removeItem(sessionKey);
    }

    function restoreState() {
        const saved = sessionStorage.getItem(sessionKey);
        if (saved) {
            try {
                const state = JSON.parse(saved);
                selectedQuestions = state.questions;
                incorrectlyAnsweredQuestions = state.incorrects;
                currentQuestionIndex = state.currentIndex;
                score = state.score;

                if (selectedQuestions && selectedQuestions.length > 0) {
                    // Wenn wir schon alle durch haben, Speicher leeren (Restart erzwingen)
                    if (currentQuestionIndex >= selectedQuestions.length) {
                        clearState();
                        return false;
                    }

                    // Zen-Modus anwenden
                    if(selectionScreen) selectionScreen.style.display = 'none';
                    if(resultScreen) resultScreen.style.display = 'none';
                    if(introHeader) introHeader.style.display = 'none';
                    if(breadcrumb) breadcrumb.style.display = 'none';
                    
                    quizScreen.style.display = 'flex';
                    displayQuestion();
                    return true;
                }
            } catch(e) {
                clearState();
            }
        }
        return false;
    }

    // 1. Initialisierung
    const chapterCards = document.querySelectorAll('.chapter-card');
    chapterCards.forEach(card => {
        card.addEventListener('click', () => {
            const checkbox = card.querySelector('input[type="checkbox"]');
            checkbox.checked = !checkbox.checked;
            card.classList.toggle('selected', checkbox.checked);
        });
    });

    if (form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const selectedChapters = Array.from(form.querySelectorAll('input[name="chapters"]:checked'))
                                       .map(cb => parseInt(cb.value) || cb.value); 
            
            if (selectedChapters.length === 0) {
                showFeedback('Bitte wähle mindestens ein Kapitel aus.', 'fail');
                return;
            }
            
            selectedQuestions = rawQuizData.filter(q => selectedChapters.includes(q.chapter));
            
            if (selectedQuestions.length === 0) {
                showFeedback('Für die ausgewählten Kapitel wurden keine Fragen gefunden.', 'fail');
                return;  
            }
            
            startQuiz(selectedQuestions);
        });
    }

    function startQuiz(questions) {
        incorrectlyAnsweredQuestions = [];
        selectedQuestions = [...questions].sort(() => Math.random() - 0.5);
        currentQuestionIndex = 0;
        score = 0;
        
        saveState(); // Speicher initialisieren
        
        if(selectionScreen) selectionScreen.style.display = 'none';
        if(resultScreen) resultScreen.style.display = 'none';
        if(introHeader) introHeader.style.display = 'none';
        if(breadcrumb) breadcrumb.style.display = 'none';
        
        quizScreen.style.display = 'flex';
        displayQuestion();
    }

    function displayQuestion() {
        feedbackArea.innerHTML = '';
        nextBtn.style.display = 'none';
        checkBtn.style.display = 'block';
        checkBtn.disabled = false;
        updateProgress();

        window.scrollTo(0, 0);

        const question = selectedQuestions[currentQuestionIndex];
        questionText.textContent = question.question;
        optionsContainer.innerHTML = '';

        if (question.type === 'radio' || question.type === 'checkbox') {
            displayStandardQuestion(question);
        } else if (question.type === 'matching') {
            displayMatchingQuestion(question);
        } else if (question.type === 'ordering') {
            displayOrderingQuestion(question);
        }
    }

    function displayStandardQuestion(question) {
        const shuffledKeys = Object.keys(question.options).sort(() => Math.random() - 0.5);
        shuffledKeys.forEach(key => {
            const optionDiv = document.createElement('div');
            const label = document.createElement('label');
            const input = document.createElement('input');
            input.type = question.type;
            input.name = 'option';
            input.value = key;
            label.appendChild(input);
            label.appendChild(document.createTextNode(' ' + question.options[key]));
            optionDiv.appendChild(label);
            optionsContainer.appendChild(optionDiv);
        });
    }

    function displayMatchingQuestion(question) {
        const container = document.createElement('div');
        container.className = 'matching-question-container';
        
        const stemsContainer = document.createElement('div');
        stemsContainer.className = 'stems-container';
        
        const responsesContainer = document.createElement('div');
        responsesContainer.className = 'responses-container';

        const randomizedStems = [...question.options.stems].sort(() => Math.random() - 0.5);

        randomizedStems.forEach(stem => {
            const stemEl = document.createElement('div');
            stemEl.className = 'draggable-option';
            stemEl.textContent = stem.text;
            stemEl.draggable = true;
            stemEl.dataset.id = stem.id;
            stemsContainer.appendChild(stemEl);
        });

        question.options.responses.forEach(response => {
            const responseItem = document.createElement('div');
            responseItem.className = 'response-item';
            
            const dropZone = document.createElement('div');
            dropZone.className = 'drop-zone';
            dropZone.dataset.targetId = response.id;
            
            const responseText = document.createElement('div');
            responseText.className = 'response-text';
            responseText.textContent = response.text;
            
            responseItem.appendChild(dropZone);
            responseItem.appendChild(responseText);
            responsesContainer.appendChild(responseItem);
        });
        
        container.appendChild(stemsContainer);
        container.appendChild(responsesContainer);
        optionsContainer.appendChild(container);

        addDragDropListeners();
    }

    function displayOrderingQuestion(question) {
        const container = document.createElement('div');
        container.className = 'ordering-question-container';
        const randomizedOptions = [...question.options].sort(() => Math.random() - 0.5);
        randomizedOptions.forEach(option => {
            const itemEl = document.createElement('div');
            itemEl.className = 'orderable-item';
            itemEl.textContent = option.text;
            itemEl.draggable = true;
            itemEl.dataset.id = option.id;
            container.appendChild(itemEl);
        });
        optionsContainer.appendChild(container);
        addOrderingDragDropListeners();
    }

    function addDragDropListeners() {
        const draggables = document.querySelectorAll('.draggable-option');
        const dropZones = document.querySelectorAll('.drop-zone');
        const stemsContainer = document.querySelector('.stems-container');

        draggables.forEach(draggable => {
            draggable.addEventListener('dragstart', () => {
                draggable.classList.add('dragging');
                draggedItem = draggable;
            });
            draggable.addEventListener('dragend', () => {
                draggable.classList.remove('dragging');
                draggedItem = null;
            });
        });

        dropZones.forEach(zone => {
            zone.addEventListener('dragover', e => {
                e.preventDefault();
                zone.classList.add('drag-over');
            });
            zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
            zone.addEventListener('drop', e => {
                e.preventDefault();
                zone.classList.remove('drag-over');
                if (draggedItem && !draggedItem.classList.contains('orderable-item')) {
                    if (zone.children.length > 0) {
                        stemsContainer.appendChild(zone.firstElementChild);
                    }
                    zone.appendChild(draggedItem);
                }
            });
        });
    }

    function addOrderingDragDropListeners() {
        const container = document.querySelector('.ordering-question-container');
        if (!container) return;
        const items = container.querySelectorAll('.orderable-item');

        items.forEach(item => {
            item.addEventListener('dragstart', () => {
                item.classList.add('dragging');
                draggedItem = item;
            });
            item.addEventListener('dragend', () => {
                item.classList.remove('dragging');
                draggedItem = null;
            });
        });

        container.addEventListener('dragover', e => {
            e.preventDefault();
            const afterElement = getDragAfterElement(container, e.clientY);
            if (draggedItem && draggedItem.classList.contains('orderable-item')) {
                if (afterElement == null) {
                    container.appendChild(draggedItem);
                } else {
                    container.insertBefore(draggedItem, afterElement);
                }
            }
        });
    }

    function getDragAfterElement(container, y) {
        const draggableElements = [...container.querySelectorAll('.orderable-item:not(.dragging)')];
        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;
            if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: child };
            } else {
                return closest;
            }
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }

    function checkAnswer() {
        checkBtn.disabled = true;
        const question = selectedQuestions[currentQuestionIndex];
        let isCorrect = false;

        if (question.type === 'radio' || question.type === 'checkbox') {
            const correctAnswers = Array.isArray(question.correct) ? question.correct.sort() : [question.correct];
            const selectedInputs = optionsContainer.querySelectorAll('input:checked');
            const userAnswers = Array.from(selectedInputs).map(input => input.value).sort();
            isCorrect = JSON.stringify(correctAnswers) === JSON.stringify(userAnswers);
            
            optionsContainer.querySelectorAll('label').forEach(label => {
                const input = label.querySelector('input');
                if (correctAnswers.includes(input.value)) {
                    label.classList.add('correct-answer');
                } else if (Array.from(selectedInputs).includes(input)) {
                    label.classList.add('wrong-answer');
                }
                input.disabled = true;
            });
        } else if (question.type === 'matching') {
            isCorrect = true;
            const dropZones = optionsContainer.querySelectorAll('.drop-zone');
            dropZones.forEach(zone => {
                const droppedEl = zone.querySelector('.draggable-option');
                const targetId = zone.dataset.targetId;
                const correctStemId = question.correct[targetId];
                
                if (droppedEl) {
                   droppedEl.draggable = false;
                   if(droppedEl.dataset.id === correctStemId) {
                       droppedEl.classList.add('correct-answer');
                   } else {
                       droppedEl.classList.add('wrong-answer');
                       isCorrect = false;
                   }
                } else {
                   isCorrect = false;
                }
            });
            if(document.querySelector('.stems-container')?.children.length > 0) {
                isCorrect = false;
            }
        } else if (question.type === 'ordering') {
            const orderedItems = optionsContainer.querySelectorAll('.orderable-item');
            const userAnswerIds = Array.from(orderedItems).map(item => item.dataset.id);
            isCorrect = JSON.stringify(userAnswerIds) === JSON.stringify(question.correct);

            orderedItems.forEach((item, index) => {
                item.draggable = false;
                if (item.dataset.id === question.correct[index]) {
                    item.classList.add('correct-answer');
                } else {
                    item.classList.add('wrong-answer');
                }
            });
        }

        if (isCorrect) {
            score++;
            showFeedback('Richtig!', 'success');
        } else {
            incorrectlyAnsweredQuestions.push(question);
            let solutionHtml = '';
            
            if (question.type === 'matching') {
                 solutionHtml = '<ul>';
                 question.options.responses.forEach(response => {
                    const correctStemId = question.correct[response.id];
                    const correctStem = question.options.stems.find(s => s.id === correctStemId);
                    solutionHtml += `<li><strong>${response.text}:</strong> ${correctStem.text}</li>`;
                });
                solutionHtml += '</ul>';
            } else if (question.type === 'ordering') {
                solutionHtml = '<ol>';
                question.correct.forEach(id => {
                    const correctOption = question.options.find(opt => opt.id === id);
                    solutionHtml += `<li>${correctOption.text}</li>`;
                });
                solutionHtml += '</ol>';
            } else {
                const correctAnswersArray = Array.isArray(question.correct) ? question.correct : [question.correct];
                solutionHtml = correctAnswersArray.map(key => question.options[key]).join(', ');
            }

            showFeedback(`Leider falsch. Die richtige Antwort ist: <br><div class="solution-wrapper">${solutionHtml}</div>`, 'fail');
        }
        
        nextBtn.style.display = 'block';
        checkBtn.style.display = 'none';
        
        // Nach Check nicht zwingend den State speichern, da wir bei Reload einfach
        // am Anfang der aktuellen (noch ungelösten) Frage neu ansetzen wollen.
    }

    function showFeedback(message, type) {
        // H2 gegen eine DIV-Klasse "result-title" getauscht (platzsparender)
        feedbackArea.innerHTML = `<div class="result-box ${type}"><div class="result-title">${message}</div></div>`;
    }

    function nextQuestion() {
        currentQuestionIndex++;
        
        if (currentQuestionIndex < selectedQuestions.length) {
            saveState(); // Speicher updaten, wenn die nächste Frage erreicht ist
            displayQuestion();
        } else {
            progressBar.style.width = `100%`;
            clearState(); // Fertig -> Speicher leeren
            showResults();
        }
    }

    function showResults() {
        quizScreen.style.display = 'none';
        resultScreen.style.display = 'block';
        
        const total = selectedQuestions.length;
        const percentage = total > 0 ? Math.round((score / total) * 100) : 0;
        
        if (scoreFinal) scoreFinal.textContent = score;
        if (totalFinal) totalFinal.textContent = total;
        if (percentageFinal) percentageFinal.textContent = percentage;
        
        resultHeadline.classList.remove('result-pass', 'result-fail');
        resultText.classList.remove('result-pass', 'result-fail');

        window.scrollTo(0, 0);

        if (percentage >= 60) {
            resultHeadline.textContent = "Glückwunsch! Quiz bestanden.";
            resultHeadline.classList.add('result-pass');
            resultText.classList.add('result-pass');
        } else {
            resultHeadline.textContent = "Nicht bestanden. Versuche es erneut.";
            resultHeadline.classList.add('result-fail');
            resultText.classList.add('result-fail');
        }
        
        if (retryIncorrectBtn) {
            retryIncorrectBtn.style.display = incorrectlyAnsweredQuestions.length > 0 ? 'block' : 'none';
        }
    }

    function updateProgress() {
        const progressPercentage = (currentQuestionIndex / selectedQuestions.length) * 100;
        if (progressBar) progressBar.style.width = `${progressPercentage}%`;
        if (progressText) progressText.textContent = `Frage ${currentQuestionIndex + 1} von ${selectedQuestions.length}`;
    }

    function restartQuiz() {
        clearState();
        if (resultScreen) resultScreen.style.display = 'none';
        if (selectionScreen) {
            selectionScreen.style.display = 'block';
            
            if(introHeader) introHeader.style.display = 'block';
            if(breadcrumb) breadcrumb.style.display = '';
            
            window.scrollTo(0, 0);
            
            const chapterCards = document.querySelectorAll('.chapter-card');
            chapterCards.forEach(card => {
                const checkbox = card.querySelector('input[type="checkbox"]');
                if (checkbox) checkbox.checked = false;
                card.classList.remove('selected');
            });
        }
    }

    if (checkBtn) checkBtn.addEventListener('click', checkAnswer);
    if (nextBtn) nextBtn.addEventListener('click', nextQuestion);
    if (restartBtn) restartBtn.addEventListener('click', restartQuiz);
    
    if (retryIncorrectBtn) {
        retryIncorrectBtn.addEventListener('click', () => {
            if (incorrectlyAnsweredQuestions.length > 0) {
                startQuiz(incorrectlyAnsweredQuestions);
            }
        });
    }

    // Beim Start der App versuchen, den Zustand aus dem Storage wiederherzustellen
    restoreState();
}