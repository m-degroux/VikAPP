<div id="courseModal" class="hidden fixed inset-0 bg-black/60 flex items-center justify-center z-[9999] p-4 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all scale-100">
        
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 id="modalCourseName" class="text-lg font-bold text-gray-800">
                </h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="p-6 max-h-[70vh] overflow-y-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50/50 sticky top-0">
                    <tr>
                        <th class="px-4 py-3 rounded-l-lg w-16 text-center">RANG</th>
                        <th class="px-4 py-3">ÉQUIPE</th>
                        <th class="px-4 py-3 text-right rounded-r-lg">TEMPS</th>
                    </tr>
                </thead>
                <tbody id="modalTableBody" class="divide-y divide-gray-100">
                    </tbody>
            </table>
        </div>
        
        <div class="bg-gray-50 px-6 py-3 flex justify-end">
            <button onclick="closeModal()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm hover:bg-gray-50 text-gray-700">
                Fermer
            </button>
        </div>
    </div>
</div>

<script>
    // Retrieves user ID if logged in, otherwise defaults to 0
    const currentRunnerId = {{ optional(Auth::user())->user_id ?? 0 }}; 

    /**
     * Opens the modal and fetches race results via API
     * @param {number} raceId - The ID of the race
     * @param {string} raceName - The name of the race to display in the header
     */
    async function openModal(raceId, raceName) {
        const modal = document.getElementById('courseModal');
        const modalTitle = document.getElementById('modalCourseName');
        const tableBody = document.getElementById('modalTableBody');

        if(!modal) return;

        // 1. UI Initialization
        modalTitle.innerText = raceName;
        tableBody.innerHTML = '<tr><td colspan="3" class="text-center py-8 text-gray-500 animate-pulse">Chargement...</td></tr>';
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevents background scrolling

        try {
            // 2. Data Fetching
            // Ensure the route Route::get('/runner/{id}', ...) is defined in web.php
            const response = await fetch(`/runner/${raceId}`); 
            
            if (!response.ok) throw new Error('Network error');

            const results = await response.json();
            tableBody.innerHTML = '';

            // Handle empty results
            if (results.length === 0) {
                 tableBody.innerHTML = '<tr><td colspan="3" class="text-center py-8 text-gray-500">Aucun résultat.</td></tr>';
                 return;
            }

            // 3. Rendering results
            results.forEach((team, index) => {
                const rank = index + 1;
                // Highlight logic if the logged-in user belongs to this team
                const isUser = (team.user_id == currentRunnerId);
                
                const rowClass = isUser ? 'bg-green-50 border-l-4 border-green-500' : 'hover:bg-gray-50';
                const rankDisplay = isUser 
                    ? `<span class="bg-green-600 text-white rounded-full w-6 h-6 flex items-center justify-center mx-auto text-xs">${rank}</span>` 
                    : rank;

                const row = `
                    <tr class="${rowClass} border-b border-gray-100 last:border-0">
                        <td class="text-center py-3 font-medium text-gray-600">${rankDisplay}</td>
                        <td class="py-3 px-4 text-gray-800 font-medium">
                            ${team.team_name}
                            ${isUser ? '<span class="ml-2 text-xs text-green-600 font-bold border border-green-200 px-1 rounded">MOI</span>' : ''}
                        </td>
                        <td class="text-right py-3 pr-4 font-mono text-gray-600 font-bold">${team.team_time}</td>
                    </tr>
                `;
                tableBody.insertAdjacentHTML('beforeend', row);
            });

        } catch (error) {
            console.error(error);
            tableBody.innerHTML = '<tr><td colspan="3" class="text-center text-red-500 py-6">Erreur de chargement</td></tr>';
        }
    }

    /**
     * Closes the results modal
     */
    function closeModal() {
        const modal = document.getElementById('courseModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto'; // Restores scrolling
    }
    
    // Accessibility: Closes modal when Escape key is pressed
    document.addEventListener('keydown', (e) => { if(e.key === "Escape") closeModal(); });
</script>