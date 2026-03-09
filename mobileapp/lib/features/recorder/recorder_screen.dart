// lib/features/recorder/recorder_screen.dart
import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../services/audio_recorder_service.dart';

class RecorderScreen extends StatefulWidget {
  const RecorderScreen({super.key});

  @override
  State<RecorderScreen> createState() => _RecorderScreenState();
}

class _RecorderScreenState extends State<RecorderScreen> {
  final AudioRecorderService _recorderService = AudioRecorderService();
  bool _isRecording = false;
  String? _lastPath;
  bool _isUploading = false;

  @override
  void dispose() {
    _recorderService.dispose();
    super.dispose();
  }

  Future<void> _toggleRecording() async {
    try {
      if (_isRecording) {
        final path = await _recorderService.stopRecording();
        setState(() {
          _isRecording = false;
          _lastPath = path;
        });
      } else {
        await _recorderService.startRecording();
        setState(() {
          _isRecording = true;
          _lastPath = null;
        });
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text("Erreur: $e")),
        );
      }
    }
  }

  Future<void> _uploadRecording() async {
    if (_lastPath == null) return;
    setState(() => _isUploading = true);
    try {
      await _recorderService.saveToSupabase(_lastPath!);
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text("Enregistrement sauvegardé avec succès !")),
        );
      }
      setState(() => _lastPath = null);
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text("Échec de la sauvegarde: $e")),
        );
      }
    } finally {
      setState(() => _isUploading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        title: Text("Enregistreur Vocal", style: GoogleFonts.outfit(fontWeight: FontWeight.bold)),
        backgroundColor: Colors.white,
        foregroundColor: const Color(0xFF444050),
        elevation: 0,
      ),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Container(
              padding: const EdgeInsets.all(40),
              decoration: BoxDecoration(
                color: _isRecording ? Colors.red.withAlpha(25) : const Color(0xFF7367F0).withAlpha(25),
                shape: BoxShape.circle,
              ),
              child: Icon(
                _isRecording ? Icons.mic_rounded : Icons.mic_none_rounded,
                size: 80,
                color: _isRecording ? Colors.red : const Color(0xFF7367F0),
              ),
            ),
            const SizedBox(height: 30),
            Text(
              _isRecording ? "Enregistrement en cours..." : "Prêt à enregistrer",
              style: GoogleFonts.outfit(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 50),
            GestureDetector(
              onTap: _toggleRecording,
              child: Container(
                width: 80,
                height: 80,
                decoration: BoxDecoration(
                  color: _isRecording ? Colors.red : const Color(0xFF7367F0),
                  shape: BoxShape.circle,
                  boxShadow: [
                    BoxShadow(
                      color: (_isRecording ? Colors.red : const Color(0xFF7367F0)).withAlpha(77),
                      blurRadius: 15,
                      offset: const Offset(0, 5),
                    ),
                  ],
                ),
                child: Icon(
                  _isRecording ? Icons.stop_rounded : Icons.play_arrow_rounded,
                  color: Colors.white,
                  size: 40,
                ),
              ),
            ),
            if (_lastPath != null) ...[
              const SizedBox(height: 40),
              ElevatedButton.icon(
                onPressed: _isUploading ? null : _uploadRecording,
                icon: _isUploading 
                    ? const SizedBox(width: 20, height: 20, child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white))
                    : const Icon(Icons.cloud_upload_rounded),
                label: Text(_isUploading ? "Envoi..." : "Sauvegarder sur le cloud"),
                style: ElevatedButton.styleFrom(
                  backgroundColor: const Color(0xFF28C76F),
                  foregroundColor: Colors.white,
                  padding: const EdgeInsets.symmetric(horizontal: 25, vertical: 15),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(15)),
                ),
              ),
            ],
          ],
        ),
      ),
    );
  }
}
