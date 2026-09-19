<?php require_once "header.html"; ?>

    <div class="decks-page">
        <div class="decks-header">
            <h1>My Decks</h1>
            <p>Manage, edit and study your decks</p>
        </div>
        <hr class="decks-divider">

        <div class="decks-toolbar">
            <div class="search-wrapper">
                <input type="text" id="searchInput" placeholder="Search decks...">
            </div>
            <button class="btn-import" onclick="openModal()">+ New Deck</button>
        </div>

        <div class="decks-grid" id="decksGrid">
            <p class="decks-empty" id="emptyMsg">Loading decks...</p>
        </div>
    </div>

    <div class="modal-overlay" id="modalOverlay">
        <div class="modal">
            <h2 id="modalTitle">New Deck</h2>
            <input type="hidden" id="deckId">
            <input type="text" id="deckName" placeholder="Deck name">
            <textarea id="deckDesc" placeholder="Description (optional)"></textarea>
            <div class="modal-buttons">
                <button class="btn-cancel" onclick="closeModal()">Cancel</button>
                <button class="btn-save" onclick="saveDeck()">Save</button>
            </div>
        </div>
    </div>

    <script>
        const API = "php/decks_api.php";
        let allDecks = [];

        async function loadDecks() {
            try {
                const res = await fetch(API);
                allDecks  = await res.json();
                renderDecks(allDecks);
            } catch (e) {
                document.getElementById("emptyMsg").textContent =
                    "Could not connect to server. Check your PHP setup.";
            }
        }

        function renderDecks(decks) {
            const grid = document.getElementById("decksGrid");
            grid.innerHTML = "";
            if (decks.length === 0) {
                grid.innerHTML = '<p class="decks-empty">No decks yet — create one!</p>';
                return;
            }
            decks.forEach(deck => {
                const date = new Date(deck.last_edited).toLocaleDateString();
                const card = document.createElement("div");
                card.className = "deck-card";
                card.innerHTML = `
                    <div class="deck-card-body">
                        <h3>${escHtml(deck.name)}</h3>
                        <p>${escHtml(deck.description || "No description")}</p>
                    </div>
                    <div class="deck-card-footer">
                        <div class="deck-card-actions">
                            <button class="btn-edit" title="Edit" onclick="event.stopPropagation(); editDeck(${deck.id})">
                                Edit
                            </button>
                        </div>
                        <span class="deck-last-edit">Edited ${date}</span>
                    </div>
                `;
                card.addEventListener("click", () => {
                    window.location.href = `deck_viewer.php?id=${deck.id}`;
                });
                grid.appendChild(card);
            });
        }

        document.getElementById("searchInput").addEventListener("input", function () {
            const q = this.value.toLowerCase();
            renderDecks(allDecks.filter(d =>
                d.name.toLowerCase().includes(q) ||
                (d.description || "").toLowerCase().includes(q)
            ));
        });

        function openModal() {
            document.getElementById("modalTitle").textContent = "New Deck";
            document.getElementById("deckId").value   = "";
            document.getElementById("deckName").value = "";
            document.getElementById("deckDesc").value = "";
            document.getElementById("modalOverlay").classList.add("open");
        }

        function editDeck(id) {
            const deck = allDecks.find(d => d.id == id);
            if (!deck) return;
            document.getElementById("modalTitle").textContent = "Edit Deck";
            document.getElementById("deckId").value   = deck.id;
            document.getElementById("deckName").value = deck.name;
            document.getElementById("deckDesc").value = deck.description || "";
            document.getElementById("modalOverlay").classList.add("open");
        }

        function closeModal() {
            document.getElementById("modalOverlay").classList.remove("open");
        }

        async function saveDeck() {
            const id   = document.getElementById("deckId").value;
            const name = document.getElementById("deckName").value.trim();
            const desc = document.getElementById("deckDesc").value.trim();
            if (!name) { alert("Please enter a deck name."); return; }
            const method = id ? "PUT" : "POST";
            const body   = id ? { id, name, description: desc } : { name, description: desc };
            await fetch(API, {
                method,
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(body)
            });
            closeModal();
            loadDecks();
        }

        function escHtml(str) {
            return str.replace(/&/g,"&amp;").replace(/</g,"&lt;")
                      .replace(/>/g,"&gt;").replace(/"/g,"&quot;");
        }

        document.getElementById("modalOverlay").addEventListener("click", function(e) {
            if (e.target === this) closeModal();
        });

        loadDecks();
    </script>

</body>
</html>