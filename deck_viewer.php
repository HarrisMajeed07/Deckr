<?php require_once "header.html"; ?>

    <div class="deck-viewer-page">
        <div class="deck-viewer-header">
            <div class="deck-viewer-title-block">
                <h1 id="deckTitle">Deck Name</h1>
                <p id="deckDescription">Description...</p>
            </div>
            <div class="deck-viewer-actions">
                <button class="dv-btn dv-btn-icon" title="Back" onclick="window.location.href='decks.php'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2.5"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H5M12 5l-7 7 7 7"/>
                    </svg>
                </button>
                <button class="dv-btn dv-btn-icon" title="Export">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2.5"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="17 8 12 3 7 8"/>
                        <line x1="12" y1="3" x2="12" y2="15"/>
                    </svg>
                </button>
                <button class="dv-btn dv-btn-icon btn-delete-deck" title="Delete">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2.5"
                         stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"/>
                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                        <path d="M10 11v6M14 11v6"/>
                        <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                    </svg>
                </button>
                <button class="dv-btn dv-btn-icon" title="Edit"
                        onclick="window.location.href='deck_edit.php?id=' + deckId">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2.5"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                </button>
            </div>
            <hr class="deck-viewer-divider">
        </div>

        <div class="deck-viewer-card-area">
            <div class="flashcard-scene" id="flashcardScene">
                <div class="flashcard" id="flashcard" onclick="flipCard()">
                    <div class="flashcard-face flashcard-front">
                        <span id="cardTerm">Loading...</span>
                    </div>
                    <div class="flashcard-face flashcard-back">
                        <span id="cardDefinition"></span>
                    </div>
                </div>
            </div>
            <p class="card-flip-hint" id="flipHint">Click the card to reveal the definition</p>
        </div>

        <div class="deck-viewer-nav">
            <button class="dv-nav-btn" id="prevBtn" onclick="changeCard(-1)">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2.5"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5M12 5l-7 7 7 7"/>
                </svg>
            </button>
            <span class="dv-card-counter" id="cardCounter">1 / 1</span>
            <button class="dv-nav-btn" id="nextBtn" onclick="changeCard(1)">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2.5"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
    </div>

    <script>
        const params  = new URLSearchParams(window.location.search);
        const deckId  = params.get("id");
        let cards       = [];
        let currentCard = 0;
        let isFlipped   = false;

        function updateCounter() {
            const total = cards.length;
            document.getElementById("cardCounter").textContent = total
                ? `${currentCard + 1} / ${total}` : "0 / 0";
            document.getElementById("prevBtn").disabled = currentCard === 0;
            document.getElementById("nextBtn").disabled = currentCard === total - 1;
        }

        function showCard(index) {
            if (!cards.length) {
                document.getElementById("cardTerm").textContent       = "No cards yet — add some!";
                document.getElementById("cardDefinition").textContent = "";
                document.getElementById("flipHint").style.display     = "none";
                return;
            }
            const card = document.getElementById("flashcard");
            card.style.transition = "none";
            card.classList.remove("flipped");
            isFlipped = false;
            document.getElementById("flipHint").style.display = "block";

            void card.offsetWidth;
            card.style.transition = "";

            document.getElementById("cardTerm").textContent       = cards[index].term       || "";
            document.getElementById("cardDefinition").textContent = cards[index].definition || "";
            updateCounter();
        }

        function flipCard() {
            if (!cards.length) return;
            const card = document.getElementById("flashcard");
            isFlipped = !isFlipped;
            card.classList.toggle("flipped", isFlipped);
            document.getElementById("flipHint").textContent = isFlipped
                ? "Click to see the term"
                : "Click the card to reveal the definition";
        }

        function changeCard(dir) {
            const newIndex = currentCard + dir;
            if (newIndex < 0 || newIndex >= cards.length) return;
            currentCard = newIndex;
            showCard(currentCard);
        }

        async function loadDeck() {
            document.getElementById("deckTitle").textContent       = "Web Development Basics";
            document.getElementById("deckDescription").textContent = "Key terms for HTML, CSS and PHP";
        }

        async function loadCards() {
            cards = [
                { id: 1, term: "HTML", definition: "HyperText Markup Language, the structure of a web page" },
                { id: 2, term: "CSS",  definition: "Cascading Style Sheets, used to style and lay out a page" },
                { id: 3, term: "PHP",  definition: "A server-side scripting language used to build dynamic sites" },
                { id: 4, term: "DOM",  definition: "Document Object Model, a tree representation of the page that JavaScript can change" }
            ];
            currentCard = 0;
            showCard(currentCard);
        }

        loadDeck();
        loadCards();
    </script>

</body>
</html>