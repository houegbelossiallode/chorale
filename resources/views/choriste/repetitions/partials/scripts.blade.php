<script>
    function recordingSystem() {
        return {
            modalOpen: false,
            lyricsOpen: false,
            isRecording: false,
            isSaving: false,
            mediaRecorder: null,
            audioChunks: [],
            audioBlob: null,
            recordingTime: 0,
            timer: null,
            currentChantId: null,
            currentRepertoireId: null,
            currentChantTitle: '',
            currentLyrics: '',

            openLyrics(lyrics, title) {
                this.currentLyrics = lyrics;
                this.currentChantTitle = title;
                this.lyricsOpen = true;
            },

            openRecordingModal(chantId, repertoireId, title) {
                this.currentChantId = chantId;
                this.currentRepertoireId = repertoireId;
                this.currentChantTitle = title;
                this.modalOpen = true;
                this.audioBlob = null;
                this.audioChunks = [];
            },

            closeModal() {
                if (this.isRecording) this.stopRecording();
                this.modalOpen = false;
            },

            formatTime(seconds) {
                const mins = Math.floor(seconds / 60);
                const secs = seconds % 60;
                return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
            },

            async startRecording() {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                    this.mediaRecorder = new MediaRecorder(stream);
                    this.audioChunks = [];

                    this.mediaRecorder.ondataavailable = (event) => {
                        this.audioChunks.push(event.data);
                    };

                    this.mediaRecorder.onstop = () => {
                        this.audioBlob = new Blob(this.audioChunks, { type: 'audio/webm' });
                    };

                    this.mediaRecorder.start();
                    this.isRecording = true;
                    this.recordingTime = 0;
                    this.timer = setInterval(() => this.recordingTime++, 1000);
                } catch (err) {
                    alert("Erreur micro : " + err.message);
                }
            },

            stopRecording() {
                this.mediaRecorder.stop();
                this.isRecording = false;
                clearInterval(this.timer);
                this.mediaRecorder.stream.getTracks().forEach(track => track.stop());
            },

            async saveRecording() {
                this.isSaving = true;
                const formData = new FormData();
                formData.append('audio', this.audioBlob, 'recording.webm');
                formData.append('chant_id', this.currentChantId);
                if (this.currentRepertoireId) {
                    formData.append('repertoire_id', this.currentRepertoireId);
                }
                formData.append('_token', '{{ csrf_token() }}');

                try {
                    const response = await fetch('{{ route("choriste.enregistrements.store") }}', {
                        method: 'POST',
                        body: formData
                    });

                    if (response.ok) {
                        window.location.reload();
                    } else {
                        const data = await response.json();
                        alert(data.message || "Erreur lors de l'enregistrement");
                    }
                } catch (err) {
                    alert("Erreur reseau : " + err.message);
                } finally {
                    this.isSaving = false;
                }
            },

            async deleteRecording(id) {
                if (!confirm('Voulez-vous supprimer cet enregistrement ?')) return;

                try {
                    const response = await fetch(`/choriste/enregistrements/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    if (response.ok) {
                        window.location.reload();
                    }
                } catch (err) {
                    alert("Erreur suppression");
                }
            }
        }
    }
</script>