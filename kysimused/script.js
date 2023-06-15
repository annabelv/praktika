var kasutajaId = "<?php echo $kasutaja_id; ?>";


document.addEventListener('DOMContentLoaded', function() {
    const startSection = document.getElementById('start-section');
    /*const introSection = document.getElementById('intro-section');*/
    const questionSection = document.getElementById('question-section');
    const endSection = document.getElementById('end-section');
    const nameInput = document.getElementById('name');
    const educationInput = document.getElementById('education');
    const professionInput = document.getElementById('profession');
    const hobbiesInput = document.getElementById('hobbies');
    const questionElement = document.getElementById('question');
    const answerButtonsElement = document.getElementById('answer-buttons');
    const nextButton = document.getElementById('next-btn');
    const endTitleElement = document.getElementById('end-title');
    const endScoreElement = document.getElementById('end-score');
  
    let currentQuestionIndex = 0;
    let score = 0;
  
    let playerData = {};
  
  
  
    
  
    // Fetching the CSV file
    fetch('temp.csv')                       // TEMP //
      .then(response => response.text())
      .then(data => {
        const csvData = parseCSVData(data);
        /*playerData = {
          name: nameInput.value,
          education: educationInput.value,
          profession: professionInput.value,
          hobbies: hobbiesInput.value
        };*/
  
        // Event listener for the start button
        document.getElementById('start-btn').addEventListener('click', () => {
          startSection.style.display = 'none';
          questionSection.style.display = 'block';
		  displayNextQuestion();
        });
  
        /*// Event listener for the start button
        document.getElementById('intro-btn').addEventListener('click', () => {
          introSection.style.display = 'block';
          questionSection.style.display = 'none';
          displayNextQuestion();
          console.log()
        });*/
  /*
          // Log the profession entered by the user
          console.log(
            "Name:", nameInput.value,
            ", Education", educationInput.value, 
            ", Profession:", professionInput.value, 
            ", Hobbies:", hobbiesInput.value, );
  */
        
  
        // Event listener for the next button
        nextButton.addEventListener('click', () => {
          const selectedAnswer = document.querySelector('.answer-btn.selected');
  
          if (selectedAnswer) {
            const scoreValue = selectedAnswer.dataset.score;
            score += parseInt(scoreValue);
            const nextQuestionIndex = parseInt(selectedAnswer.dataset.nextquestion);
            console.log("Score for previous answer: " + scoreValue);         /////////
            
            if (nextQuestionIndex >= 0) {
              currentQuestionIndex = nextQuestionIndex;
              displayNextQuestion();
            } else {
              showEndScreen();
            }

            console.log("Score for previous answer: " + scoreValue);
            if (scoreValue < 1) {
              playVideo('video1');
            } else if (scoreValue >= 0) {
              playVideo('video2');
            }
          }
          console.log("Current score: " + score);
        });
  
        // Function to parse the CSV data
        function parseCSVData(csvData) {
          const lines = csvData.split('\n');
          const header = lines[0].split(';');
          const questions = [];
  
          for (let i = 1; i < lines.length; i++) {
            const currentLine = lines[i].split(';');
            const question = {};
  
            for (let j = 0; j < header.length; j++) {
              question[header[j]] = currentLine[j];
            }
  
            questions.push(question);
          }
  
          return questions;
        }
  
        // Function to display the next question
        function displayNextQuestion() {
          const currentQuestion = csvData[currentQuestionIndex];
          const question = currentQuestion['Question'];
  
          questionElement.innerText = question;
          answerButtonsElement.innerHTML = '';
  
          for (let i = 1; i <= 4; i++) {
            const answer = currentQuestion[`Answer${i}`];
            const score = currentQuestion[`Score${i}`];
            const nextQuestion = currentQuestion[`NextQuestion${i}`];
  
            createAnswerButton(answer, score, nextQuestion);
          }
  
          currentQuestionIndex = findQuestionIndex(currentQuestionIndex);
        }
  
        // Function to find the index of the next question
        function findQuestionIndex(currentIndex) {
          const currentQuestion = csvData[currentIndex];
     //     console.log("currentIndex: " + currentIndex)
          for (let i = 1; i <= 4; i++) {
            const nextQuestion = currentQuestion[`NextQuestion${i}`].trim();
      //      console.log("currentQuestion: " + currentIndex)
            if (nextQuestion !== '') {
              const nextIndex = csvData.findIndex(question => question['QuestionNumber'].trim() === nextQuestion);
  
              if (nextIndex !== -1) {
                return nextIndex;
              }
            }
          }
  
          return -1;
        }
  
        // Function to create an answer button
        function createAnswerButton(answer, score, nextQuestion) {
          const button = document.createElement('button');
          button.innerText = answer;
          button.classList.add('answer-btn');
          button.dataset.score = score;
          button.dataset.nextquestion = nextQuestion !== '' ? nextQuestion : -1;
          button.addEventListener('click', selectAnswer);
          answerButtonsElement.appendChild(button);
        }
  
        // Function to handle the answer selection
        function selectAnswer(e) {
          const selectedButton = e.target;
  
          const answerButtons = document.querySelectorAll('.answer-btn');
          answerButtons.forEach(button => {
            button.classList.remove('selected');
          });
  
          selectedButton.classList.add('selected');
          nextButton.disabled = false;
        }
  
        // Function to show the end screen
        function showEndScreen() {
          questionSection.style.display = 'none';
          endSection.style.display = 'block';

          const endScore = calculateEndScore();
          endScoreElement.innerText = endScore;

          let endTitle = '';
          let retryButton = document.createElement('button');
          let feedbackButton = document.createElement('button');

          const percentage = endScore;

          if (endScore <= 30) {
            endTitle = 'Kahjuks kukkusid läbi, sinu punktiskoor oli ' + percentage.toFixed(2) + '%';
          } else if (endScore <= 60) {
            endTitle = 'Said intervjuust läbi, sinu punktiskoor oli ' + percentage.toFixed(2) + '%';
          } else {
            endTitle = 'Title C';
          }

          endTitleElement.innerText = endTitle;

          retryButton.innerText = 'Sooritan uuesti';
          retryButton.addEventListener('click', function() {
            window.location.href = 'https://greeny.cs.tlu.ee/~valjanna/test2.php'; // Replace 'retry.html' with the actual URL of the retry page
          });

          feedbackButton.innerText = 'Annan tagasisidet';
          feedbackButton.addEventListener('click', function() {
            window.location.href = 'https://greeny.cs.tlu.ee/~arulmere/statistika/'; // Replace 'feedback.html' with the actual URL of the feedback page
          });

          endSection.appendChild(retryButton);
          endSection.appendChild(feedbackButton);
        }

  
        // Function to calculate the end score
        function calculateEndScore() {
          const education = parseInt(playerData.education);
          const profession = parseInt(playerData.profession);
          const hobbies = parseInt(playerData.hobbies);
  
          let endScore = score;
  
          submitEndScore(score);
          updateUnansweredQuestions(endScore);

          return endScore
  
        }
  
        
          
  
        function submitEndScore(score) {
            // Create an XMLHttpRequest object
            var xhr = new XMLHttpRequest();
          
            // Prepare the data to be sent to the PHP file
            var data = new FormData();
            data.append('kasutaja_id', kasutajaId);
            data.append('score', score);
          
            // Set up the AJAX request
            xhr.open('POST', 'submit_data.php', true);
          
            // Define the callback function for when the request is complete
            xhr.onload = function() {
              if (xhr.status === 200) {
                // Request was successful
                console.log(xhr.responseText);
              } else {
                // Request failed
                console.error('Error:', xhr.status);
              }
            };
          
            // Send the request
            xhr.send(data);
          }

          /*uuendame vastamta küsimusi*/
          
          function updateUnansweredQuestions(score) {
            var xhr = new XMLHttpRequest();
            const lastQuestionNumber = csvData.length - 1;
            console.log(lastQuestionNumber + "last");
            const unansweredQuestions = lastQuestionNumber - currentQuestionIndex;
            console.log(unansweredQuestions + " unanswered");
          
            // Prepare the data to be sent to the PHP file
            var data = new FormData();
            data.append('unansweredQuestions', unansweredQuestions);
          
            // Set up the AJAX request
            xhr.open('POST', 'update_unanswered_questions.php', true);
          
            // Define the callback function for when the request is complete
            xhr.onload = function () {
              if (xhr.status === 200) {
                // Request was successful
                console.log(xhr.responseText);
              } else {
                // Request failed
                console.error('Error:', xhr.status);
              }
            };
          
            // Send the request with the data
            xhr.send(data);
          }
    
  
        function playVideo(videoId) {
          var defaultVideo = document.getElementById("default-video");
          var video1 = document.getElementById("video1");
          var video2 = document.getElementById("video2");
    
          defaultVideo.style.display = "none";
          video1.style.display = "none";
          video2.style.display = "none";
    
          if (videoId === "video1") {
            defaultVideo.pause();
            defaultVideo.style.display = "none";
            video1.style.display = "block";
            video1.play();
            video1.onended = function () {
              video1.style.display = "none";
              defaultVideo.style.display = "block";
            };
          } else if (videoId === "video2") {
            defaultVideo.pause();
            defaultVideo.style.display = "none";
            video2.style.display = "block";
            video2.play();
            video2.onended = function () {
              video2.style.display = "none";
              defaultVideo.style.display = "block";
            };
          }
        }
  
      });
  });
  
	var media = document.getElementById("intro-video");
	media.addEventListener("ended", BtnVisible);
	   function BtnVisible(){

		var btnVisible = document.getElementById("start-btn");
		btnVisible.style.visibility = "visible";

	   };