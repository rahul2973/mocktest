
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mock Test Simulator</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .question-container {
            transition: opacity 0.3s ease;
        }
        .option-card {
            transition: all 0.2s ease;
        }
        .option-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .option-card.selected {
            border-color: #3B82F6;
            background-color: #EFF6FF;
        }
        .progress-bar {
            transition: width 0.5s ease;
        }
        #results-section {
            display: none;
        }
        .nav-btn.answered {
            background-color: #10B981;
            color: white;
        }
        .nav-btn.marked {
            background-color: #F59E0B;
            color: white;
        }
        .nav-btn.visited {
            background-color: #3B82F6;
            color: white;
        }
        .nav-btn.current {
            background-color: #EF4444;
            color: white;
        }
        .sidebar {
            height: calc(100vh - 2rem);
            position: sticky;
            top: 1rem;
        }
        .question-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(40px, 1fr));
            gap: 0.5rem;
        }
        @media (max-width: 1024px) {
            .sidebar {
                height: auto;
                position: static;
            }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen font-sans">
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Mock Exam Simulator</h1>
                    <p class="text-gray-600">Test your knowledge with this comprehensive mock test</p>
                </div>
                <div class="text-right">
                    <div class="timer-container bg-red-50 rounded-full px-4 py-2">
                        <span class="text-red-600 font-bold text-lg" id="timer">120:00</span>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Time Remaining</p>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Sidebar with Question Navigation -->
            <div class="lg:w-80 bg-white rounded-lg shadow-md p-6 sidebar">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Question Palette</h3>
                
                <!-- Legend -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-red-500 rounded-full mr-2"></div>
                            <span class="text-sm text-gray-600">Current Question</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-green-500 rounded-full mr-2"></div>
                            <span class="text-sm text-gray-600">Answered</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-yellow-500 rounded-full mr-2"></div>
                            <span class="text-sm text-gray-600">Marked for Review</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-blue-500 rounded-full mr-2"></div>
                            <span class="text-sm text-gray-600">Visited</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-gray-300 rounded-full mr-2"></div>
                            <span class="text-sm text-gray-600">Not Visited</span>
                        </div>
                    </div>
                </div>

                <!-- Question Navigation Grid -->
                <div class="mb-4">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-sm font-medium text-gray-700">All Questions</span>
                        <span class="text-sm text-gray-500" id="total-questions">200</span>
                    </div>
                    <div class="question-grid max-h-96 overflow-y-auto p-2" id="question-nav">
                        <!-- Navigation buttons will be generated dynamically -->
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="p-4 bg-blue-50 rounded-lg">
                    <h4 class="text-sm font-medium text-blue-800 mb-2">Summary</h4>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div class="text-blue-600">Answered: <span id="answered-count">0</span></div>
                        <div class="text-yellow-600">Marked: <span id="marked-count">0</span></div>
                        <div class="text-blue-600">Visited: <span id="visited-count">0</span></div>
                        <div class="text-gray-600">Not Visited: <span id="not-visited-count">200</span></div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="flex-1">
                <!-- Progress Bar -->
                <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Progress</span>
                        <span class="text-sm font-medium text-gray-700" id="progress-text">1/200</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="progress-bar bg-blue-600 h-2.5 rounded-full" style="width: 0.5%"></div>
                    </div>
                </div>

                <!-- Main Content -->
                <div id="test-section">
                    <!-- Question -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6 question-container">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">Question 1</span>
                                <span class="ml-3 text-sm text-gray-500">Multiple Choice</span>
                            </div>
                            <span class="text-sm text-yellow-600 font-medium mark-status" id="mark-status" style="display: none;">
                                <i class="fas fa-bookmark mr-1"></i>Marked for Review
                            </span>
                        </div>
                        
                        <h2 class="text-xl font-semibold text-gray-800 mb-6" id="question-text">
                            Which of the following is NOT a JavaScript data type?
                        </h2>

                        <!-- Options -->
                        <div class="space-y-3" id="options-container">
                            <!-- Options will be generated dynamically -->
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between bg-white rounded-lg shadow-md p-4">
                        <button id="prev-btn" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors" disabled>
                            <i class="fas fa-arrow-left mr-2"></i>Previous
                        </button>
                        
                        <div class="flex gap-3">
                            <button id="mark-btn" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors">
                                <i class="fas fa-bookmark mr-2"></i>Mark for Review
                            </button>
                            <button id="next-btn" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                Next<i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Results Section -->
                <div id="results-section" class="bg-white rounded-lg shadow-md p-8 text-center">
                    <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-trophy text-green-600 text-4xl"></i>
                    </div>
                    
                    <h2 class="text-3xl font-bold text-gray-800 mb-4">Test Completed!</h2>
                    <p class="text-gray-600 mb-8">You've successfully completed the mock test</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <div class="text-3xl font-bold text-blue-600 mb-2" id="score">0</div>
                            <div class="text-blue-600">Score</div>
                        </div>
                        
                        <div class="bg-green-50 p-4 rounded-lg">
                            <div class="text-3xl font-bold text-green-600 mb-2" id="correct">0</div>
                            <div class="text-green-600">Correct Answers</div>
                        </div>
                        
                        <div class="bg-red-50 p-4 rounded-lg">
                            <div class="text-3xl font-bold text-red-600 mb-2" id="incorrect">0</div>
                            <div class="text-red-600">Incorrect Answers</div>
                        </div>
                    </div>
                    
                    <button id="retake-btn" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-redo mr-2"></i>Retake Test
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Generate 200 mock questions
        const generateQuestions = () => {
            const questions = [];
            const topics = [
                "JavaScript", "HTML", "CSS", "React", "Node.js", 
                "Python", "Java", "SQL", "Algorithms", "Data Structures"
            ];
            
            const questionTemplates = [
                "Which of the following is NOT a {topic} concept?",
                "What does {topic} stand for?",
                "Which {topic} method is used for {action}?",
                "What is the purpose of {concept} in {topic}?",
                "How does {topic} handle {situation}?",
                "Which {topic} feature provides {benefit}?",
                "What is the time complexity of {algorithm} in {topic}?",
                "Which {topic} framework is best for {purpose}?",
                "How do you implement {pattern} in {topic}?",
                "What are the advantages of using {technology} with {topic}?"
            ];
            
            const actions = ["sorting", "filtering", "rendering", "parsing", "validating"];
            const concepts = ["variables", "functions", "classes", "modules", "components"];
            const benefits = ["performance", "security", "scalability", "maintainability", "readability"];
            
            for (let i = 1; i <= 200; i++) {
                const topic = topics[Math.floor(Math.random() * topics.length)];
                const template = questionTemplates[Math.floor(Math.random() * questionTemplates.length)];
                
                let questionText = template
                    .replace('{topic}', topic)
                    .replace('{action}', actions[Math.floor(Math.random() * actions.length)])
                    .replace('{concept}', concepts[Math.floor(Math.random() * concepts.length)])
                    .replace('{benefit}', benefits[Math.floor(Math.random() * benefits.length)]);
                
                questions.push({
                    id: i,
                    question: questionText,
                    options: [
                        `Option A for Question ${i}`,
                        `Option B for Question ${i}`,
                        `Option C for Question ${i}`,
                        `Option D for Question ${i}`
                    ],
                    correctAnswer: String.fromCharCode(65 + Math.floor(Math.random() * 4)) // A, B, C, or D
                });
            }
            return questions;
        };

        const questions = generateQuestions();

        // App state
        let currentQuestion = 1;
        let userAnswers = {};
        let questionStatus = {};
        let timerInterval;
        let timeLeft = 7200; // 2 hours for 200 questions

        // DOM Elements
        const timerEl = document.getElementById('timer');
        const progressTextEl = document.getElementById('progress-text');
        const progressBarEl = document.querySelector('.progress-bar');
        const testSectionEl = document.getElementById('test-section');
        const resultsSectionEl = document.getElementById('results-section');
        const scoreEl = document.getElementById('score');
        const correctEl = document.getElementById('correct');
        const incorrectEl = document.getElementById('incorrect');
        const questionNavEl = document.getElementById('question-nav');
        const questionTextEl = document.getElementById('question-text');
        const optionsContainerEl = document.getElementById('options-container');
        const markStatusEl = document.getElementById('mark-status');
        const markBtnEl = document.getElementById('mark-btn');
        const nextBtnEl = document.getElementById('next-btn');
        const prevBtnEl = document.getElementById('prev-btn');
        const retakeBtnEl = document.getElementById('retake-btn');
        const totalQuestionsEl = document.getElementById('total-questions');
        const answeredCountEl = document.getElementById('answered-count');
        const markedCountEl = document.getElementById('marked-count');
        const visitedCountEl = document.getElementById('visited-count');
        const notVisitedCountEl = document.getElementById('not-visited-count');

        // Initialize the test
        function initTest() {
            totalQuestionsEl.textContent = questions.length;
            
            // Initialize question status
            questions.forEach((_, index) => {
                questionStatus[index + 1] = 'not-visited';
            });
            questionStatus[1] = 'current';
            
            createNavigationButtons();
            updateProgress();
            updateQuestionDisplay();
            updateSummaryStats();
            startTimer();
            setupEventListeners();
        }

        // Create navigation buttons
        function createNavigationButtons() {
            questionNavEl.innerHTML = '';
            questions.forEach((_, index) => {
                const questionNum = index + 1;
                const button = document.createElement('button');
                button.className = 'nav-btn w-10 h-10 rounded-lg font-bold text-sm flex items-center justify-center transition-colors';
                button.textContent = questionNum;
                button.dataset.question = questionNum;
                button.addEventListener('click', () => navigateToQuestion(questionNum));
                
                // Set initial state
                if (questionNum === currentQuestion) {
                    button.classList.add('bg-red-500', 'text-white');
                } else {
                    button.classList.add('bg-gray-300', 'text-gray-700');
                }
                
                questionNavEl.appendChild(button);
            });
        }

        // Update navigation buttons
        function updateNavigationButtons() {
            document.querySelectorAll('.nav-btn').forEach(button => {
                const questionNum = parseInt(button.dataset.question);
                
                // Remove all status classes
                button.classList.remove('bg-red-500', 'bg-green-500', 'bg-yellow-500', 'bg-blue-500', 'bg-gray-300');
                
                // Set appropriate class based on status
                if (questionNum === currentQuestion) {
                    button.classList.add('bg-red-500', 'text-white');
                } else {
                    switch (questionStatus[questionNum]) {
                        case 'answered':
                            button.classList.add('bg-green-500', 'text-white');
                            break;
                        case 'marked':
                            button.classList.add('bg-yellow-500', 'text-white');
                            break;
                        case 'visited':
                            button.classList.add('bg-blue-500', 'text-white');
                            break;
                        default:
                            button.classList.add('bg-gray-300', 'text-gray-700');
                    }
                }
            });
        }

        // Update summary statistics
        function updateSummaryStats() {
            let answered = 0;
            let marked = 0;
            let visited = 0;
            let notVisited = 0;
            
            Object.values(questionStatus).forEach(status => {
                switch (status) {
                    case 'answered': answered++; break;
                    case 'marked': marked++; break;
                    case 'visited': visited++; break;
                    case 'current': visited++; break;
                    default: notVisited++;
                }
            });
            
            answeredCountEl.textContent = answered;
            markedCountEl.textContent = marked;
            visitedCountEl.textContent = visited;
            notVisitedCountEl.textContent = notVisited;
        }

        // Update progress bar and text
        function updateProgress() {
            const progress = (currentQuestion / questions.length) * 100;
            progressBarEl.style.width = `${progress}%`;
            progressTextEl.textContent = `${currentQuestion}/${questions.length}`;
        }

        // Start the countdown timer
        function startTimer() {
            updateTimerDisplay();
            timerInterval = setInterval(() => {
                timeLeft--;
                updateTimerDisplay();
                
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    submitTest();
                }
            }, 1000);
        }

        function updateTimerDisplay() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            timerEl.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            // Change color when time is running out
            if (timeLeft < 300) {
                timerEl.classList.add('text-red-600');
            }
        }

        // Setup event listeners
        function setupEventListeners() {
            // Mark for review button
            markBtnEl.addEventListener('click', toggleMarkForReview);
            
            // Next button
            nextBtnEl.addEventListener('click', () => {
                if (currentQuestion < questions.length) {
                    navigateToQuestion(currentQuestion + 1);
                } else {
                    submitTest();
                }
            });

            // Previous button
            prevBtnEl.addEventListener('click', () => {
                if (currentQuestion > 1) {
                    navigateToQuestion(currentQuestion - 1);
                }
            });

            // Retake test button
            retakeBtnEl.addEventListener('click', resetTest);
        }

        // Toggle mark for review
        function toggleMarkForReview() {
            if (questionStatus[currentQuestion] === 'marked') {
                questionStatus[currentQuestion] = userAnswers[currentQuestion] ? 'answered' : 'visited';
                markStatusEl.style.display = 'none';
                markBtnEl.innerHTML = '<i class="fas fa-bookmark mr-2"></i>Mark for Review';
            } else {
                questionStatus[currentQuestion] = 'marked';
                markStatusEl.style.display = 'inline';
                markBtnEl.innerHTML = '<i class="fas fa-times mr-2"></i>Unmark';
            }
            updateNavigationButtons();
            updateSummaryStats();
        }

        // Select an option
        function selectOption(option) {
            userAnswers[currentQuestion] = option;
            questionStatus[currentQuestion] = 'answered';
            
            // Update UI
            document.querySelectorAll('.option-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            document.querySelector(`[data-option="${option}"]`).classList.add('selected');
            
            // Update navigation and summary
            updateNavigationButtons();
            updateSummaryStats();
            
            // If marked, unmark it
            if (markStatusEl.style.display !== 'none') {
                toggleMarkForReview();
            }
        }

        // Navigate to a specific question
        function navigateToQuestion(questionNumber) {
            // Update previous question status if not answered or marked
            if (questionStatus[currentQuestion] === 'current') {
                questionStatus[currentQuestion] = userAnswers[currentQuestion] ? 'answered' : 'visited';
            }
            
            currentQuestion = questionNumber;
            questionStatus[currentQuestion] = 'current';
            
            updateProgress();
            updateQuestionDisplay();
            updateNavigationButtons();
            updateSummaryStats();
        }

        // Update question display
        function updateQuestionDisplay() {
            const question = questions[currentQuestion - 1];
            
            // Update question number and text
            document.querySelector('.question-container span.bg-blue-100').textContent = `Question ${currentQuestion}`;
            questionTextEl.textContent = question.question;
            
            // Update options
            optionsContainerEl.innerHTML = '';
            question.options.forEach((option, index) => {
                const letter = String.fromCharCode(65 + index);
                const optionCard = document.createElement('div');
                optionCard.className = 'option-card border-2 border-gray-200 rounded-lg p-4 cursor-pointer';
                optionCard.dataset.option = letter;
                optionCard.innerHTML = `
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                            <span class="font-bold">${letter}</span>
                        </div>
                        <span>${option}</span>
                    </div>
                `;
                
                optionCard.addEventListener('click', () => selectOption(letter));
                
                if (userAnswers[currentQuestion] === letter) {
                    optionCard.classList.add('selected');
                }
                
                optionsContainerEl.appendChild(optionCard);
            });
            
            // Update mark status
            if (questionStatus[currentQuestion] === 'marked') {
                markStatusEl.style.display = 'inline';
                markBtnEl.innerHTML = '<i class="fas fa-times mr-2"></i>Unmark';
            } else {
                markStatusEl.style.display = 'none';
                markBtnEl.innerHTML = '<i class="fas fa-bookmark mr-2"></i>Mark for Review';
            }
            
            // Update navigation buttons
            prevBtnEl.disabled = currentQuestion === 1;
            
            // Update next button text
            if (currentQuestion === questions.length) {
                nextBtnEl.innerHTML = 'Submit <i class="fas fa-check ml-2"></i>';
            } else {
                nextBtnEl.innerHTML = 'Next <i class="fas fa-arrow-right ml-2"></i>';
            }
        }

        // Submit the test and show results
        function submitTest() {
            clearInterval(timerInterval);
            
            // Calculate results
            let correct = 0;
            let incorrect = 0;
            
            questions.forEach((question, index) => {
                if (userAnswers[index + 1] === question.correctAnswer) {
                    correct++;
                } else {
                    incorrect++;
                }
            });
            
            const score = Math.round((correct / questions.length) * 100);
            
            // Update results display
            scoreEl.textContent = score;
            correctEl.textContent = correct;
            incorrectEl.textContent = incorrect;
            
            // Show results section
            testSectionEl.style.display = 'none';
            resultsSectionEl.style.display = 'block';
        }

        // Reset the test
        function resetTest() {
            currentQuestion = 1;
            userAnswers = {};
            questionStatus = {};
            timeLeft = 7200;
            
            // Reset UI
            testSectionEl.style.display = 'block';
            resultsSectionEl.style.display = 'none';
            
            // Reset timer display
            timerEl.classList.remove('text-red-600');
            
            initTest();
        }

        // Initialize the test when page loads
        document.addEventListener('DOMContentLoaded', initTest);
    </script>
</body>
</html>


