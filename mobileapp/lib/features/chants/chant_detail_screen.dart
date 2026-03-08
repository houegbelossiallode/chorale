import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:supabase_flutter/supabase_flutter.dart';
import 'package:audioplayers/audioplayers.dart';

class ChantDetailScreen extends StatefulWidget {
  final dynamic chant;
  const ChantDetailScreen({super.key, required this.chant});

  @override
  State<ChantDetailScreen> createState() => _ChantDetailScreenState();
}

class _ChantDetailScreenState extends State<ChantDetailScreen> {
  final _supabase = Supabase.instance.client;
  final _audioPlayer = AudioPlayer();
  bool _isPlaying = false;
  Duration _duration = Duration.zero;
  Duration _position = Duration.zero;
  List<dynamic> _audioFiles = [];
  bool _isLoadingAudio = true;
  String? _currentAudioUrl;

  @override
  void initState() {
    super.initState();
    _fetchAudioFiles();

    _audioPlayer.onPlayerStateChanged.listen((state) {
      if (mounted) {
        setState(() => _isPlaying = state == PlayerState.playing);
      }
    });

    _audioPlayer.onDurationChanged.listen((newDuration) {
      if (mounted) {
        setState(() => _duration = newDuration);
      }
    });

    _audioPlayer.onPositionChanged.listen((newPosition) {
      if (mounted) {
        setState(() => _position = newPosition);
      }
    });
  }

  @override
  void dispose() {
    _audioPlayer.dispose();
    super.dispose();
  }

  Future<void> _fetchAudioFiles() async {
    try {
      final data = await _supabase
          .from('fichier_chants')
          .select('id, type, file_path, pupitre_id')
          .eq('chant_id', widget.chant['id'])
          .eq('type', 'audio');

      if (mounted) {
        setState(() {
          _audioFiles = data;
          _isLoadingAudio = false;
          if (_audioFiles.isNotEmpty) {
            _currentAudioUrl = _audioFiles.first['file_path'];
          }
        });
      }
    } catch (e) {
      if (mounted) {
        setState(() => _isLoadingAudio = false);
      }
    }
  }

  Future<void> _togglePlay() async {
    if (_currentAudioUrl == null) return;

    if (_isPlaying) {
      await _audioPlayer.pause();
    } else {
      await _audioPlayer.play(UrlSource(_currentAudioUrl!));
    }
  }

  String _formatDuration(Duration duration) {
    String twoDigits(int n) => n.toString().padLeft(2, "0");
    String twoDigitMinutes = twoDigits(duration.inMinutes.remainder(60));
    String twoDigitSeconds = twoDigits(duration.inSeconds.remainder(60));
    return "$twoDigitMinutes:$twoDigitSeconds";
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        backgroundColor: Colors.transparent,
        elevation: 0,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back_ios_new_rounded, color: Color(0xFF444050)),
          onPressed: () => Navigator.pop(context),
        ),
        actions: [
          IconButton(
            icon: const Icon(Icons.favorite_border_rounded, color: Color(0xFF444050)),
            onPressed: () {},
          ),
          const SizedBox(width: 5),
        ],
      ),
      body: SingleChildScrollView(
        child: Padding(
          padding: const EdgeInsets.symmetric(horizontal: 25),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const SizedBox(height: 10),
              _buildChantInfo(),
              const SizedBox(height: 40),
              _buildAudioPlayer(),
              const SizedBox(height: 40),
              _buildLyricsSection(),
              const SizedBox(height: 50),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildChantInfo() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Container(
          height: 200,
          width: double.infinity,
          decoration: BoxDecoration(
            gradient: const LinearGradient(
              colors: [Color(0xFF7367F0), Color(0xFF9E95F5)],
              begin: Alignment.topLeft,
              end: Alignment.bottomRight,
            ),
            borderRadius: BorderRadius.circular(30),
            boxShadow: [
              BoxShadow(
                color: const Color(0xFF7367F0).withOpacity(0.3),
                blurRadius: 20,
                offset: const Offset(0, 10),
              ),
            ],
          ),
          child: const Center(
            child: Icon(Icons.music_note_rounded, color: Colors.white, size: 80),
          ),
        ),
        const SizedBox(height: 30),
        Text(
          widget.chant['title'] ?? 'Sans titre',
          style: GoogleFonts.outfit(fontSize: 26, fontWeight: FontWeight.bold, color: const Color(0xFF444050)),
        ),
        Text(
          widget.chant['composer'] ?? 'Compositeur inconnu',
          style: GoogleFonts.outfit(fontSize: 16, color: Colors.slate.shade400, fontWeight: FontWeight.w500),
        ),
      ],
    );
  }

  Widget _buildAudioPlayer() {
    if (_isLoadingAudio) {
      return const Center(child: CircularProgressIndicator());
    }

    if (_audioFiles.isEmpty) {
      return Container(
        padding: const EdgeInsets.all(20),
        decoration: BoxDecoration(
          color: Colors.slate.shade50,
          borderRadius: BorderRadius.circular(20),
        ),
        child: const Row(
          children: [
            Icon(Icons.info_outline, color: Colors.slate),
            SizedBox(width: 10),
            Text("Aucun fichier audio disponible", style: TextStyle(color: Colors.slate)),
          ],
        ),
      );
    }

    return Container(
      padding: const EdgeInsets.all(25),
      decoration: BoxDecoration(
        color: const Color(0xFFFAFAFE),
        borderRadius: BorderRadius.circular(30),
        border: Border.all(color: Colors.slate.shade100),
      ),
      child: Column(
        children: [
          Slider(
            min: 0,
            max: _duration.inSeconds.toDouble(),
            value: _position.inSeconds.toDouble(),
            activeColor: const Color(0xFF7367F0),
            inactiveColor: const Color(0xFF7367F0).withOpacity(0.1),
            onChanged: (value) async {
              final position = Duration(seconds: value.toInt());
              await _audioPlayer.seek(position);
            },
          ),
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 20),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text(_formatDuration(_position), style: const TextStyle(color: Colors.slate, fontSize: 12)),
                Text(_formatDuration(_duration), style: const TextStyle(color: Colors.slate, fontSize: 12)),
              ],
            ),
          ),
          const SizedBox(height: 20),
          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              IconButton(
                icon: const Icon(Icons.replay_10_rounded, size: 30, color: Color(0xFF444050)),
                onPressed: () => _audioPlayer.seek(_position - const Duration(seconds: 10)),
              ),
              const SizedBox(width: 20),
              GestureDetector(
                onTap: _togglePlay,
                child: Container(
                  height: 65,
                  width: 65,
                  decoration: const BoxDecoration(
                    color: Color(0xFF7367F0),
                    shape: BoxShape.circle,
                  ),
                  child: Icon(
                    _isPlaying ? Icons.pause_rounded : Icons.play_arrow_rounded,
                    color: Colors.white,
                    size: 35,
                  ),
                ),
              ),
              const SizedBox(width: 20),
              IconButton(
                icon: const Icon(Icons.forward_10_rounded, size: 30, color: Color(0xFF444050)),
                onPressed: () => _audioPlayer.seek(_position + const Duration(seconds: 10)),
              ),
            ],
          ),
          if (_audioFiles.length > 1) ...[
            const SizedBox(height: 25),
            const Text("Versions disponibles :", style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14)),
            const SizedBox(height: 10),
            SizedBox(
              height: 40,
              child: ListView.builder(
                scrollDirection: Axis.horizontal,
                itemCount: _audioFiles.length,
                itemBuilder: (context, index) {
                  final audio = _audioFiles[index];
                  final isCurrent = _currentAudioUrl == audio['file_path'];
                  return Padding(
                    padding: const EdgeInsets.only(right: 10),
                    child: ChoiceChip(
                      label: Text("Version ${index + 1}"),
                      selected: isCurrent,
                      onSelected: (selected) {
                        if (selected) {
                          setState(() {
                            _currentAudioUrl = audio['file_path'];
                            _isPlaying = false;
                            _position = Duration.zero;
                            _duration = Duration.zero;
                          });
                          _audioPlayer.stop();
                        }
                      },
                      selectedColor: const Color(0xFF7367F0).withOpacity(0.1),
                      labelStyle: TextStyle(
                        color: isCurrent ? const Color(0xFF7367F0) : Colors.slate,
                        fontWeight: isCurrent ? FontWeight.bold : FontWeight.normal,
                      ),
                    ),
                  );
                },
              ),
            ),
          ],
        ],
      ),
    );
  }

  Widget _buildLyricsSection() {
    final lyrics = widget.chant['parole'];
    if (lyrics == null || lyrics.isEmpty) {
      return const SizedBox.shrink();
    }

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          "Paroles",
          style: GoogleFonts.outfit(fontSize: 20, fontWeight: FontWeight.bold, color: const Color(0xFF444050)),
        ),
        const SizedBox(height: 15),
        Container(
          width: double.infinity,
          padding: const EdgeInsets.all(20),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(25),
            border: Border.all(color: Colors.slate.shade100),
          ),
          child: Text(
            lyrics,
            style: GoogleFonts.outfit(fontSize: 15, color: const Color(0xFF444050), height: 1.6),
          ),
        ),
      ],
    );
  }
}
