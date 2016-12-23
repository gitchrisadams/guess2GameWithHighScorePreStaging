//Game variables
// Number to guess. Generate a random number between 1 and 99:
var mysteryNumber = Math.floor(Math.random() * 1000);
  
var playersGuess = 0;                                    // Number of guesses by player.
var guessesRemaining = 20;                              // Number of guesses remaining.
var guessesMade = 0;                                     // Number of remaining guesses.
var gameState = "";                                      // For displaying current game state to player.
var gameWon = false;                                     // Determine if player has won the game.
var highscore = 0;
var initials = "";

// Log value of mystery number for testing:
//console.log(mysteryNumber);

//The input and output fields
var input = document.querySelector("#input");
var output = document.querySelector("#output");

//The button
var button = document.querySelector("button");
button.style.cursor = "pointer";
button.addEventListener("click", clickHandler, false);

//The arrow
var arrow = document.querySelector("#arrow");

// Add event listener for keyboard input:
window.addEventListener("keydown", keydownHandler, false);
  
// Function to run when enter key is pressed. Ascii key 13 is enter.
function keydownHandler(event){
  if(event.keyCode === 13){
    validateInput();
  }
}

// Function to display the scale/slider display graphic:
function render()
{
  //Position the arrow
  //Multipy the players guess by 3 to get the
  //corrent pixel position on the scale
  arrow.style.left = playersGuess + "px";
}
  
// Starts function when button is clicked:
function clickHandler()
{
  validateInput();
}

// Validate input was a number not a String:
function validateInput(){
  // Convert player's guess to a number:
  playersGuess = parseInt(input.value);
  
  // If the player's guess is not a number, ask them to
  // enter a number:
  if(isNaN(playersGuess)){
    output.innerHTML = "Please enter a number.";
  }
  
  // It is a number so continue with the game:
  else{
    playGame();
  }
}

// Game guessing logic:
function playGame()
{
  // Decrement guesses remaining since player has made a guess:
  guessesRemaining--;
    
  // Increment the guesses made by one:
  guessesMade++;
  
  // Store current game state:
  gameState = " Guess: " + guessesMade + ", Remaining: " + guessesRemaining;
    
  // Convert string data to integer:
  playersGuess = parseInt(input.value);
  
  // Display game state based on player's guess:
  if(playersGuess > mysteryNumber)
  {
    output.innerHTML = "That's too high. " + gameState;
    
    // Check for the end of the game:
    if(guessesRemaining < 1){
      endGame();
    }
  }
  else if(playersGuess < mysteryNumber)
  {
    output.innerHTML = "That's too low. " + gameState;
    
    // Check for end of game:
    if(guessesRemaining < 1){
      endGame();
    }
  }
  else if(playersGuess === mysteryNumber)
  {
    // Player won so set gameWon to true and call endGame() method:
    gameWon = true;
    endGame();
  }
  
  //Update the graphic display
  render();
}

// Display proper message based on the player's guess:
function endGame(){
  if(gameWon){
    output.innerHTML = "<h2>" + "Yes, it's " + mysteryNumber + "!" + "</h2><br>" 
      + "It only took you " + guessesMade + " guesses.";
    
    initials = prompt("Enter your initials");
  }
  else{
    output.innerHTML = "No more guesses left!" + "<br>"     
    + "The number was: " + mysteryNumber +".";
  }
  
  window.location.href = "http://guess3.christopheradams.com/highscore.php?guessesMade=" + guessesMade + "&playerGuess=" + playersGuess + "&initials=" + initials;
  
  
  // Disable button and enter key since game is finished:
  // Disable button:
  button.removeEventListener("click", clickHandler, false);
  button.disabled = true;
  
  // Disable enter key:
  window.removeEventListener("keydown", keydownHandler, false);
  
  // Disable input field:
  input.disabled = true;
}