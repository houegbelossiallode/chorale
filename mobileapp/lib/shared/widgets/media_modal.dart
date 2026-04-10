import 'dart:io';
import 'package:flutter/material.dart';
import 'package:youtube_player_flutter/youtube_player_flutter.dart';
import 'package:just_audio/just_audio.dart' as ja;
import 'package:google_fonts/google_fonts.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:flutter_html/flutter_html.dart';

class MediaModal extends StatefulWidget {
  final String title;
  final String url;
  final String type; // 'youtube', 'video', 'audio'
  final String? lyrics;

  const MediaModal({
    super.key,
    required this.title,
    required this.url,
    required this.type,
    this.lyrics,
  });

  static void show(BuildContext context, {required String title, required String url, required String type, String? lyrics}) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (context) => MediaModal(title: title, url: url, type: type, lyrics: lyrics),
    );
  }

  @override
  State<MediaModal> createState() => _MediaModalState();
}

class _MediaModalState extends State<MediaModal> {
  YoutubePlayerController? _ytController;
  final ja.AudioPlayer _audioPlayer = ja.AudioPlayer();
  bool _isAudioPlaying = false;
  Duration _duration = Duration.zero;
  Duration _position = Duration.zero;

  @override
  void initState() {
    super.initState();
    if (widget.type == 'youtube' || widget.type == 'lien_youtube') {
      final videoId = YoutubePlayer.convertUrlToId(widget.url);
      if (videoId != null) {
        _ytController = YoutubePlayerController(
          initialVideoId: videoId,
          flags: const YoutubePlayerFlags(
            autoPlay: true,
            mute: false,
          ),
        );
      }
    } else if (widget.type == 'audio') {
      _initAudio();
    }
  }

  Future<void> _initAudio() async {
    _audioPlayer.playerStateStream.listen((state) {
      if (mounted) setState(() => _isAudioPlaying = state.playing);
    });
    _audioPlayer.durationStream.listen((d) {
      if (mounted) setState(() => _duration = d ?? Duration.zero);
    });
    _audioPlayer.positionStream.listen((p) {
      if (mounted) setState(() => _position = p);
    });
    try {
      String finalUrl = widget.url;
      if (!finalUrl.startsWith('http') && !finalUrl.startsWith('https') && !finalUrl.startsWith('asset')) {
        final baseUrl = dotenv.env['BACKEND_URL'] ?? "https://romero-38dc.onrender.com";
        finalUrl = "$baseUrl/$finalUrl";
      }
      
      // Fix for iOS: Percent-encode special characters in the URL
      finalUrl = Uri.encodeFull(finalUrl);
      debugPrint("MediaModal: Loading audio from URL: $finalUrl");

      await _audioPlayer.setUrl(finalUrl);
      await _audioPlayer.play();
    } catch (e) {
      debugPrint("MediaModal: Audio error: $e");
      if (mounted) {
        String message = "Erreur de lecture : ${e.toString()}";
        
        if (Platform.isIOS) {
          if (e.toString().contains("-11828") || widget.url.endsWith(".webm")) {
            message = "Format non supporté sur iPhone (.webm).";
          }
        }
        
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(message), backgroundColor: Colors.red),
        );
      }
    }
  }

  @override
  void dispose() {
    _ytController?.dispose();
    _audioPlayer.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Container(
      constraints: BoxConstraints(
        maxHeight: MediaQuery.of(context).size.height * 0.85,
      ),
      decoration: const BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.vertical(top: Radius.circular(30)),
      ),
      padding: EdgeInsets.only(
        bottom: MediaQuery.of(context).padding.bottom + 20,
        top: 20,
        left: 20,
        right: 20,
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Container(
            width: 40,
            height: 4,
            decoration: BoxDecoration(
              color: Colors.grey[300],
              borderRadius: BorderRadius.circular(2),
            ),
          ),
          const SizedBox(height: 20),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Expanded(
                child: Text(
                  widget.title,
                  style: GoogleFonts.outfit(
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                    color: const Color(0xFF444050),
                  ),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
              ),
              IconButton(
                icon: const Icon(Icons.close),
                onPressed: () => Navigator.pop(context),
              ),
            ],
          ),
          const SizedBox(height: 20),
          if (widget.lyrics != null && widget.lyrics!.isNotEmpty)
            Flexible(
              child: Container(
                width: double.infinity,
                margin: const EdgeInsets.only(bottom: 20),
                padding: const EdgeInsets.all(15),
                decoration: BoxDecoration(
                  color: Colors.grey[50],
                  borderRadius: BorderRadius.circular(15),
                  border: Border.all(color: Colors.grey[200]!),
                ),
                child: SingleChildScrollView(
                  child: Html(
                    data: widget.lyrics!,
                    style: {
                      "body": Style(
                        fontSize: FontSize(14.0),
                        color: const Color(0xFF444050),
                        lineHeight: LineHeight.number(1.3),
                        margin: Margins.zero,
                        padding: HtmlPaddings.zero,
                      ),
                      "p": Style(
                        margin: Margins.zero,
                        padding: HtmlPaddings.zero,
                      ),
                      "div": Style(
                        margin: Margins.zero,
                        padding: HtmlPaddings.zero,
                      ),
                    },
                  ),
                ),
              ),
            ),
          if (_ytController != null)
            ClipRRect(
              borderRadius: BorderRadius.circular(20),
              child: YoutubePlayer(
                controller: _ytController!,
                showVideoProgressIndicator: true,
                progressIndicatorColor: const Color(0xFF7367F0),
              ),
            )
          else if (widget.type == 'audio')
            _buildAudioPlayer()
          else
            const Center(child: Text("Contenu non supporté en prévisualisation")),
          const SizedBox(height: 20),
        ],
      ),
    );
  }

  Widget _buildAudioPlayer() {
    return Column(
      children: [
        Slider(
          value: _position.inSeconds.toDouble().clamp(0, _duration.inSeconds.toDouble() > 0 ? _duration.inSeconds.toDouble() : 1.0),
          max: _duration.inSeconds.toDouble() > 0 ? _duration.inSeconds.toDouble() : 1.0,
          onChanged: (val) => _audioPlayer.seek(Duration(seconds: val.toInt())),
          activeColor: const Color(0xFF7367F0),
        ),
        Row(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            IconButton(
              icon: Icon(_isAudioPlaying ? Icons.pause_circle_filled : Icons.play_circle_filled),
              iconSize: 64,
              color: const Color(0xFF7367F0),
              onPressed: () {
                if (_isAudioPlaying) {
                  _audioPlayer.pause();
                } else {
                  _audioPlayer.play();
                }
              },
            ),
          ],
        ),
      ],
    );
  }
}
