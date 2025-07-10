<div x-data="postItNotes()" 
     x-init="init()"
     class="fixed bottom-4 right-4 z-50 select-none">
    
    <!-- Post-it principal -->
    <div class="relative" x-show="!minimized" x-transition>
        <!-- Post-it jaune avec ombre et texture -->
        <div class="bg-yellow-200 border-l-4 border-yellow-300 post-it-shadow post-it-texture transform rotate-1 hover:rotate-0 transition-transform duration-200"
             style="width: 300px; min-height: 220px;">
            
            <!-- Header du post-it -->
            <div class="flex justify-between items-center p-3 pb-2 border-b border-yellow-300/50 select-none">
                <h3 class="text-sm font-semibold text-yellow-800 flex items-center">
                    📝 Notes Verdon Tourisme
                </h3>
                <div class="flex gap-2">
                    <button @click="minimized = true; saveState()" 
                            class="text-yellow-700 hover:text-yellow-900 text-lg leading-none px-1"
                            title="Réduire">
                        −
                    </button>
                    <button @click="clearNotes()" 
                            class="text-yellow-700 hover:text-yellow-900 text-sm leading-none px-1"
                            title="Effacer les notes">
                        🗑️
                    </button>
                </div>
            </div>

            <!-- Zone de texte -->
            <div class="p-3">
                <textarea 
                    x-model="notes"
                    @input="saveNotes(); autoResize($event)"
                    placeholder="💭 Notez vos informations importantes ici...

📋 Exemples d'utilisation :
• Coordonnées du visiteur
• Demandes spéciales
• Infos sur le groupe
• Remarques importantes

💡 Ces notes restent visibles sur toutes les pages
🔄 Sauvegarde automatique"
                    class="w-full bg-transparent border-none resize-none text-yellow-900 placeholder-yellow-600 text-sm leading-relaxed focus:outline-none select-text"
                    style="font-family: 'Comic Sans MS', cursive, sans-serif; min-height: 120px; max-height: 300px;"></textarea>
            </div>

            <!-- Compteur de caractères -->
            <div class="px-3 pb-2 text-right">
                <span class="text-xs text-yellow-700" x-text="notes.length + ' caractères'"></span>
            </div>

            <!-- Petit coin replié pour l'effet réaliste -->
            <div class="absolute top-0 right-0 w-8 h-8 bg-yellow-100 border-l border-b border-yellow-400 transform rotate-45 translate-x-4 -translate-y-4 shadow-sm"></div>
            
            <!-- Petite ombre du coin replié -->
            <div class="absolute top-1 right-1 w-6 h-6 bg-yellow-300/30 transform rotate-45 translate-x-3 -translate-y-3"></div>
        </div>
    </div>

    <!-- Version minimisée avec indicateur de contenu -->
    <div x-show="minimized" x-transition class="relative">
        <button @click="minimized = false; saveState()" 
                class="bg-yellow-400 hover:bg-yellow-300 text-yellow-800 px-4 py-3 rounded-full shadow-lg transform rotate-2 hover:rotate-0 transition-all duration-200 flex items-center gap-2">
            📝 
            <div class="flex flex-col items-start">
                <span class="text-sm font-medium">Notes</span>
                <span x-show="notes.length > 0" class="text-xs opacity-75" x-text="notes.length + ' car.'"></span>
            </div>
            <span x-show="notes.length > 0" class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
        </button>
    </div>
</div>

<script>
function postItNotes() {
    return {
        notes: '',
        minimized: false,
        
        init() {
            // Charger les notes depuis localStorage
            this.notes = localStorage.getItem('postit_notes') || '';
            this.minimized = localStorage.getItem('postit_minimized') === 'true';
            
            
            // Auto-resize initial du textarea
            this.$nextTick(() => {
                const textarea = this.$el.querySelector('textarea');
                if (textarea) {
                    this.autoResize({ target: textarea });
                }
            });
        },
        
        saveNotes() {
            localStorage.setItem('postit_notes', this.notes);
            
            // Envoyer un événement personnalisé pour notifier les autres composants
            window.dispatchEvent(new CustomEvent('postit-updated', { 
                detail: { notes: this.notes } 
            }));
        },
        
        saveState() {
            localStorage.setItem('postit_minimized', this.minimized);
        },
        
        
        clearNotes() {
            if (confirm('🗑️ Êtes-vous sûr de vouloir effacer toutes vos notes ?\n\nCette action est irréversible.')) {
                this.notes = '';
                localStorage.removeItem('postit_notes');
                
                // Auto-resize après effacement
                const textarea = this.$el.querySelector('textarea');
                if (textarea) {
                    this.autoResize({ target: textarea });
                }
            }
        },
        
        autoResize(event) {
            const textarea = event.target;
            // Reset height to calculate scrollHeight correctly
            textarea.style.height = 'auto';
            
            // Calculate new height based on content
            const newHeight = Math.min(Math.max(textarea.scrollHeight, 120), 300);
            textarea.style.height = newHeight + 'px';
        },
        
    }
}

// Ajout d'un raccourci clavier pour ouvrir/fermer les notes
document.addEventListener('keydown', function(event) {
    // Ctrl/Cmd + Shift + N pour toggle les notes
    if ((event.ctrlKey || event.metaKey) && event.shiftKey && event.key === 'N') {
        event.preventDefault();
        const postItElement = document.querySelector('[x-data*="postItNotes"]');
        if (postItElement && postItElement._x_dataStack) {
            const component = postItElement._x_dataStack[0];
            component.minimized = !component.minimized;
            component.saveState();
        }
    }
});
</script>
