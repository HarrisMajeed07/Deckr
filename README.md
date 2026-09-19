# Deckr
Deckr is a simple flashcard webapp that allows students to make flashcards for exam revision. It has a built in pomodoro timer so they can time their revision and has a build in flashcard editor and management system

Deckr is a website that allows the user to create, organize and store digital flashcards that they can use for things such as studying, memorising or repetitive learning. The tool allows them to create a deck which stores flashcards, each card has a term and a definition that the user can flip between. The site has a flipping system so the user can flip between all the cards in the deck and quickly flip between the term and the definition. The goal of decker is to create a fast, clean tool that doesn’t have ads or third-party aspects, so the user feels comfortable using it. It’s easy to use and very minimal which creates its unique design. 

I built the website using HTML5, CSS, JavaScript and PHP. The database is a MySQL database. Some of the key features my website has been the user authentication, the ability to create, edit and store cards. The ability to store cards inside of decks that all have title and description. There is a built-in study timer that the student can use to time how much they study, the timer has a pause/continue button, start and stop button and works in sections of 25mins. 


Fonts
The fonts that I used I took from google fonts. I found 2 that I liked and just copied the link to import them into the CSS style sheet. The first font I used was Boldonse, which I used for the headings, titles and logo because of its large, bold size. I used it because I wanted Deckr to have its own identity that could be recognized so I chose a font that looks somewhat unique. To compliment this font, I also used another font called inter. The reason for this was because it was simple, readable and complimented the bold font very nicely. It’s based on sans-serif and is apparently designed for easy reading. I liked that it looked bubbly but wasn’t to over the top. Both of these fonts were imported via single google fonts @import in the style sheet.

# Key features
## User authentication
The website has a login page for the user to log in to their account and save their flashcards. It was build in PHP. The passwords are hashed using the PHP password_hash() and password_verify() functions. The sessions are managed on the server’s side to ensure that the log in state is maintained on all the pages.

## Flashcard deck management. 
The user can create, view and edit their flashcards and their decks via a MySQL database. Each deck has a name, a description and can hold a unlimited number of cards. Each card has a term and a definition. All the data is stored in the database on the server. 

## Interactive card study mode
The study interface (deck viewer page) has the flashcard in the centre of the screen. It shows only one card at a time. The JavaScript handles the card flip animation which allows the user to click the card to reveal the other side (definition) the user can navigate through the cards by clicking the buttons at the bottom. The page has no distractions such as ads which ensures the student stays focused. 

##Pomodoro timer
There is a built in 25-minute pomodoro study timer on its dedicated page. The timer is implemented using JavaScript and continues running in the background regardless of the page the student is on. It uses sessionStorage to keep the timer state consistent. The user can start the timer, pause/continue the timer and reset it by clicking the buttons. 

## Multimedia (instruction video)
Some people struggle with reading and following instructions, so I included a short video that demonstrates how to navigate and use the website. It is located on the about page just above the written instructions. The video is embedded into the page and the file is stored in the image’s directory along with the logo. It is delivered via the HTML <video> element with multiple source format fallback (MP4, WebM, MOV) to make sure it works regardless of the browser. 

## Responsive design
The website uses CSS based responsive layout that will move and adapt according to different screen sizes. A mobile friendly navigation and fluid layout ensures that its usable on both desktop and mobile.
Accessibility

