<?php require_once "header.html"; ?>

    <div class="deck-viewer-page">
        <div class="deck-viewer-header">
            <div class="deck-viewer-title-block">
                <h1 id="deckTitle">Deck Name</h1>
                <p id="deckDescription">Description...</p>
            </div>
            <div class="deck-viewer-actions">
                <button class="dv-btn dv-btn-icon" title="Back" onclick="window.location.href='deck_viewer.php?id=' + deckId">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2.5"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H5M12 5l-7 7 7 7"/>
                    </svg>
                </button>
                <button class="dv-btn dv-btn-icon" title="Add Card" onclick="addCard()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2.5"
                         stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"/>
                        <line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                </button>
                <button class="dv-btn dv-btn-save" title="Save All" onclick="saveAll()">
                    Save
                </button>
            </div>
            <hr class="deck-viewer-divider">
        </div>

        <div class="cards-edit-list" id="cardsEditList">
            <!-- Cards injected here -->
        </div>
    </div>

    <script>
        const params = new URLSearchParams(window.location.search);
        const deckId = params.get("id");
        const CARDS_API = "php/cards_api.php";

        let cards = [];

        async function loadDeck() {
            if (!deckId) return;
            try {
                const res  = await fetch(`php/decks_api.php?id=${deckId}`);
                const deck = await res.json();
                document.getElementById("deckTitle").textContent       = deck.name        || "Deck Name";
                document.getElementById("deckDescription").textContent = deck.description || "";
            } catch (e) {
                console.error("Could not load deck:", e);
            }
        }

        async function loadCards() {
            try {
                const res = await fetch(`${CARDS_API}?deck_id=${deckId}`);
                cards = await res.json();
                renderCards();
            } catch (e) {
                console.error("Could not load cards:", e);
            }
        }

        function renderCards() {
            const list = document.getElementById("cardsEditList");
            list.innerHTML = "";
            if (cards.length === 0) {
                list.innerHTML = '<p class="decks-empty">No cards yet — click + to add one!</p>';
                return;
            }
            cards.forEach((card, index) => {
                const el = document.createElement("div");
                el.className = "card-edit-item";
                el.dataset.id = card.id || "";
                el.innerHTML = `
                    <div class="card-edit-number">${index + 1}</div>
                    <div class="card-edit-fields">
                        <input  class="card-edit-term" type="text"
                                placeholder="Term..."
                                value="${escHtml(card.term || '')}">
                        <textarea class="card-edit-def"
                                  placeholder="Definition...">${escHtml(card.definition || '')}</textarea>
                    </div>
                    <button class="card-edit-delete" title="Delete card" onclick="deleteCard(this, ${index})">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2.5"
                             stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                `;
                list.appendChild(el);
            });
        }

        function addCard() {
            cards.push({ id: null, term: "", definition: "" });
            renderCards();
            // Scroll to the new card
            const items = document.querySelectorAll(".card-edit-item");
            items[items.length - 1].scrollIntoView({ behavior: "smooth", block: "center" });
        }

        function deleteCard(btn, index) {
            cards.splice(index, 1);
            renderCards();
        }

        async function saveAll() {
            const items = document.querySelectorAll(".card-edit-item");
            const payload = [];
            items.forEach(item => {
                const term = item.querySelector(".card-edit-term").value.trim();
                const def  = item.querySelector(".card-edit-def").value.trim();
                if (!term && !def) return; // skip blank cards
                payload.push({
                    id:         item.dataset.id || null,
                    deck_id:    deckId,
                    term:       term,
                    definition: def
                });
            });

            try {
                await fetch(CARDS_API, {
                    method:  "POST",
                    headers: { "Content-Type": "application/json" },
                    body:    JSON.stringify({ deck_id: deckId, cards: payload })
                });
                window.location.href = `deck_viewer.php?id=${deckId}`;
            } catch (e) {
                alert("Could not save cards. Check your server.");
            }
        }

        function escHtml(str) {
            return String(str)
                .replace(/&/g,"&amp;").replace(/</g,"&lt;")
                .replace(/>/g,"&gt;").replace(/"/g,"&quot;");
        }

        loadDeck();
        loadCards();
    </script>

</body>
</html>