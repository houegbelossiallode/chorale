import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:choralia/shared/widgets/media_modal.dart';
import 'package:choralia/shared/widgets/pdf_viewer_screen.dart';
import 'dart:async';
import '../../services/event_service.dart';
import '../../services/repetition_service.dart';
import '../../services/audio_recorder_service.dart';
import '../../services/recording_service.dart';
import '../chants/chant_detail_screen.dart';
import 'package:flutter_html/flutter_html.dart';
import 'package:just_audio/just_audio.dart';
import 'package:supabase_flutter/supabase_flutter.dart';

class RepertoireScreen extends StatefulWidget {
  final String? eventId;
  final String? repetitionId;
  final String title;

  const RepertoireScreen({
    super.key,
    this.eventId,
    this.repetitionId,
    required this.title,
  });

  @override
  State<RepertoireScreen> createState() => _RepertoireScreenState();
}

class _RepertoireScreenState extends State<RepertoireScreen> {
  final EventService _eventService = EventService();
  final RepetitionService _repetitionService = RepetitionService();
  final AudioRecorderService _recorderService = AudioRecorderService();
  final RecordingService _recordingService = RecordingService();

  bool _isLoading = true;
  List<Map<String, dynamic>> _repertoireItems = [];

  // État de l'enregistrement
  bool _isRecording = false;
  int _recordingSeconds = 0;
  String? _currentRepertoireItemId;
  Timer? _timer;

  // État de la lecture
  AudioPlayer? _audioPlayer;
  String? _playingRecordingId;

  @override
  void initState() {
    super.initState();
    _fetchData();
  }

  @override
  void dispose() {
    _timer?.cancel();
    _recorderService.dispose();
    _audioPlayer?.dispose();
    super.dispose();
  }

  Future<void> _fetchData() async {
    try {
      List<Map<String, dynamic>> data;
      if (widget.eventId != null) {
        data = await _eventService.fetchRepertoire(widget.eventId!);
      } else if (widget.repetitionId != null) {
        data = await _repetitionService.fetchRepertoire(widget.repetitionId!);
      } else {
        data = [];
      }

      if (mounted) {
        setState(() {
          _repertoireItems = data;
          _isLoading = false;
        });
      }
    } catch (e) {
      if (mounted) {
        setState(() => _isLoading = false);
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text("Erreur: $e"), backgroundColor: Colors.red),
        );
      }
    }
  }
  Future<void> _toggleRecording(String chantId, String repertoireId) async {
    try {
      if (_isRecording) {
        final path = await _recorderService.stopRecording();
        _timer?.cancel();
        if (mounted) setState(() => _isRecording = false);

        if (path != null && mounted) {
          _showSaveDialog(path, chantId, repertoireId);
        }
      } else {
        await _recorderService.startRecording();
        if (mounted) {
          setState(() {
            _isRecording = true;
            _currentRepertoireItemId = repertoireId;
            _recordingSeconds = 0;
          });
        }
        _timer = Timer.periodic(const Duration(seconds: 1), (timer) {
          if (mounted) {
            setState(() => _recordingSeconds++);
          } else {
            timer.cancel();
          }
        });
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text("Micro error: $e")));
      }
    }
  }

  void _showSaveDialog(String path, String chantId, String repertoireId) {
    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (dialogContext) => AlertDialog(
        title: const Text("Enregistrement terminé"),
        content: Text("Voulez-vous sauvegarder votre voix pour ce chant ? (${_recordingSeconds}s)"),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(dialogContext),
            child: const Text("Annuler"),
          ),
          ElevatedButton(
            onPressed: () async {
              Navigator.pop(dialogContext);
              if (mounted) setState(() => _isLoading = true);
              try {
                await _recordingService.saveRecording(
                  localPath: path,
                  chantId: chantId,
                  repertoireId: repertoireId,
                );
                if (mounted) {
                  ScaffoldMessenger.of(context).showSnackBar(
                    SnackBar(
                      content: Row(
                        children: [
                          const Icon(Icons.check_circle_rounded, color: Colors.white),
                          const SizedBox(width: 10),
                          Text("Voix sauvegardée avec succès !", style: GoogleFonts.outfit(color: Colors.white, fontWeight: FontWeight.bold)),
                        ],
                      ),
                      backgroundColor: const Color(0xFF28C76F),
                      behavior: SnackBarBehavior.floating,
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                      duration: const Duration(seconds: 3),
                    ),
                  );
                  _fetchData();
                }
              } catch (e) {
                if (mounted) {
                  setState(() => _isLoading = false);
                  ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text("Erreur sauvegarde: $e")));
                }
              }
            },
            child: const Text("Sauvegarder"),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFFAFAFE),
      body: CustomScrollView(
        slivers: [
          _buildSliverAppBar(),
          if (_isLoading)
            const SliverFillRemaining(child: Center(child: CircularProgressIndicator()))
          else if (_repertoireItems.isEmpty)
            SliverFillRemaining(child: _buildEmptyState())
          else
            _buildRepertoireList(),
        ],
      ),
    );
  }

  Widget _buildSliverAppBar() {
    return SliverAppBar(
      expandedHeight: 140,
      pinned: true,
      backgroundColor: Colors.white,
      foregroundColor: const Color(0xFF444050),
      elevation: 0,
      flexibleSpace: FlexibleSpaceBar(
        titlePadding: const EdgeInsets.only(left: 60, bottom: 16),
        title: Text(
          widget.title,
          style: GoogleFonts.outfit(fontWeight: FontWeight.bold, fontSize: 10, color: const Color(0xFF444050)),
        ),
        background: Container(
          decoration: BoxDecoration(
            gradient: LinearGradient(
              colors: [const Color(0xFF7367F0).withAlpha(25), Colors.white],
              begin: Alignment.topCenter,
              end: Alignment.bottomCenter,
            ),
          ),
        ),
      ),
      actions: [
        if (widget.eventId != null)
          IconButton(
            icon: const Icon(Icons.picture_as_pdf_outlined, color: Color(0xFFEA5455)),
            onPressed: () {
              final baseUrl = dotenv.env['BACKEND_URL'] ?? "https://chorale.onrender.com";
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) => PdfViewerScreen(
                    title: "Répertoire PDF",
                    url: "$baseUrl/choriste/agenda/${widget.eventId}/repertoire/pdf",
                  ),
                ),
              );
            },
          ),
        const SizedBox(width: 10),
      ],
    );
  }

  Widget _buildEmptyState() {
    return Column(
      mainAxisAlignment: MainAxisAlignment.center,
      children: [
        const Icon(Icons.music_off_outlined, size: 80, color: Color(0xFFD0D2D6)),
        const SizedBox(height: 15),
        Text("Aucun chant n'est encore programmé", style: GoogleFonts.outfit(color: Colors.grey[500], fontSize: 16)),
      ],
    );
  }

  Widget _buildRepertoireList() {
    Map<String, List<Map<String, dynamic>>> grouped = {};
    for (var item in _repertoireItems) {
      String partie = item['partie_events']?['titre'] ?? "Chants";
      if (!grouped.containsKey(partie)) grouped[partie] = [];
      grouped[partie]!.add(item);
    }

    // Sort parts by the 'ordre' of the first item in each group
    var sortedKeys = grouped.keys.toList();
    sortedKeys.sort((a, b) {
      final orderA = grouped[a]![0]['partie_events']?['ordre'] ?? 999;
      final orderB = grouped[b]![0]['partie_events']?['ordre'] ?? 999;
      return orderA.compareTo(orderB);
    });

    return SliverPadding(
      padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 20),
      sliver: SliverList(
        delegate: SliverChildBuilderDelegate(
          (context, index) {
            String partie = sortedKeys[index];
            List<Map<String, dynamic>> items = grouped[partie]!;

            return Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Padding(
                  padding: const EdgeInsets.only(bottom: 12, top: 20),
                  child: Row(
                    children: [
                      Container(width: 4, height: 16, decoration: BoxDecoration(color: const Color(0xFF7367F0), borderRadius: BorderRadius.circular(2))),
                      const SizedBox(width: 8),
                      Text(partie.toUpperCase(), style: GoogleFonts.outfit(fontSize: 12, fontWeight: FontWeight.bold, color: const Color(0xFF7367F0), letterSpacing: 1.2)),
                    ],
                  ),
                ),
                ...items.map((item) => _buildPremiumChantCard(item)),
              ],
            );
          },
          childCount: grouped.length,
        ),
      ),
    );
  }

  Widget _buildPremiumChantCard(Map<String, dynamic> item) {
    final chant = item['chants'];
    final List<dynamic> ressources = chant['fichier_chants'] ?? [];
    final List<dynamic> enregistrements = chant['enregistrements'] ?? [];
    final bool isRecorded = enregistrements.isNotEmpty;
    final bool isRecordingThis = _isRecording && _currentRepertoireItemId == item['id'].toString();

    return Container(
      margin: const EdgeInsets.only(bottom: 15),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(24),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withAlpha(8),
            blurRadius: 15,
            offset: const Offset(0, 5),
          ),
        ],
      ),
      child: Material(
        color: Colors.transparent,
        child: InkWell(
          onTap: () => Navigator.push(context, MaterialPageRoute(builder: (context) => ChantDetailScreen(chant: chant))),
          borderRadius: BorderRadius.circular(24),
          child: Padding(
            padding: const EdgeInsets.all(18),
            child: Column(
              children: [
                Row(
                  children: [
                    Container(
                      width: 50,
                      height: 50,
                      decoration: BoxDecoration(
                        gradient: LinearGradient(colors: [const Color(0xFF7367F0), const Color(0xFF9E95F5)]),
                        borderRadius: BorderRadius.circular(16),
                      ),
                      child: const Icon(Icons.music_note_rounded, color: Colors.white, size: 24),
                    ),
                    const SizedBox(width: 15),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(chant['title'] ?? 'Sans titre', style: GoogleFonts.outfit(fontWeight: FontWeight.bold, fontSize: 13, color: const Color(0xFF444050))),
                          Text(chant['composer'] ?? 'Compositeur inconnu', style: GoogleFonts.outfit(fontSize: 11, color: Colors.grey[500])),
                        ],
                      ),
                    ),
                    if (isRecorded)
                      Container(
                        padding: const EdgeInsets.all(5),
                        decoration: const BoxDecoration(color: Color(0xFF28C76F), shape: BoxShape.circle),
                        child: const Icon(Icons.check_rounded, color: Colors.white, size: 12),
                      ),
                  ],
                ),
                // Show existing recordings with play/delete
                if (isRecorded) ...[
                  const SizedBox(height: 10),
                  ...enregistrements.map((rec) => _buildRecordingBar(rec)),
                ],
                const SizedBox(height: 18),
                Row(
                  children: [
                    Expanded(
                      child: SingleChildScrollView(
                        scrollDirection: Axis.horizontal,
                        child: Row(
                          children: [
                            if (chant['parole'] != null)
                              _buildResourcePill(Icons.article_outlined, "Paroles", const Color(0xFF7367F0), () => _showLyrics(chant['title'], chant['parole'])),
                            if (chant['file_path'] != null)
                              _buildResourcePill(Icons.picture_as_pdf_outlined, "Partition", const Color(0xFFEA5455), () => _launchResource({'type': 'partition', 'file_path': chant['file_path'], 'label': 'Partition Principale'})),
                            ...ressources.map((r) => _buildResourcePill(
                                  r['type'] == 'audio' ? Icons.headset_rounded : r['type'] == 'youtube' ? Icons.play_circle_fill : Icons.description_outlined,
                                  "${r['type'].toString().toUpperCase()} ${r['pupitres']?['name'] != null ? '(${r['pupitres']['name']})' : ''}",
                                  r['type'] == 'youtube' ? const Color(0xFFEA5455) : const Color(0xFF444050),
                                  () => _launchResource(r, chant['parole']),
                                )),
                          ],
                        ),
                      ),
                    ),
                    if (!isRecorded || isRecordingThis)
                      _buildRecordButton(item['id'].toString(), chant['id'].toString(), isRecordingThis),
                  ],
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildResourcePill(IconData icon, String label, Color color, VoidCallback onTap) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        margin: const EdgeInsets.only(right: 8),
        padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
        decoration: BoxDecoration(color: color.withAlpha(15), borderRadius: BorderRadius.circular(12), border: Border.all(color: color.withAlpha(25))),
        child: Row(
          children: [
            Icon(icon, size: 14, color: color),
            const SizedBox(width: 5),
            Text(label, style: GoogleFonts.outfit(fontSize: 10, fontWeight: FontWeight.bold, color: color)),
          ],
        ),
      ),
    );
  }

  Widget _buildRecordButton(String repoId, String chantId, bool isRecording) {
    return GestureDetector(
      onTap: () => _toggleRecording(chantId, repoId),
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
        decoration: BoxDecoration(
          color: isRecording ? const Color(0xFFEA5455) : const Color(0xFF7367F0).withAlpha(25),
          borderRadius: BorderRadius.circular(15),
        ),
        child: Row(
          children: [
            Icon(isRecording ? Icons.stop_rounded : Icons.mic_rounded, size: 16, color: isRecording ? Colors.white : const Color(0xFF7367F0)),
            if (isRecording) ...[
              const SizedBox(width: 5),
              Text("${_recordingSeconds}s", style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 12)),
            ],
          ],
        ),
      ),
    );
  }

  Widget _buildPlayButton(String filePath) {
    return GestureDetector(
      onTap: () {
        MediaModal.show(
          context,
          title: "Mon Enregistrement",
          url: filePath,
          type: 'audio',
        );
      },
      child: Container(
        padding: const EdgeInsets.all(8),
        decoration: BoxDecoration(
          color: const Color(0xFF7367F0).withAlpha(25),
          shape: BoxShape.circle,
        ),
        child: const Icon(Icons.play_arrow_rounded, color: Color(0xFF7367F0), size: 20),
      ),
    );
  }

  Widget _buildRecordingBar(Map<String, dynamic> rec) {
    final String recId = rec['id'].toString();
    final String? filePath = rec['file_path'];
    final bool isPlaying = _playingRecordingId == recId;

    return Container(
      margin: const EdgeInsets.only(bottom: 6),
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
      decoration: BoxDecoration(
        color: const Color(0xFF7367F0).withAlpha(12),
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: const Color(0xFF7367F0).withAlpha(30)),
      ),
      child: Row(
        children: [
          const Icon(Icons.mic_rounded, size: 16, color: Color(0xFF7367F0)),
          const SizedBox(width: 8),
          Expanded(
            child: Text("Ma voix déposée", style: GoogleFonts.outfit(fontSize: 12, fontWeight: FontWeight.bold, color: const Color(0xFF7367F0))),
          ),
          // Play/Stop button
          GestureDetector(
            onTap: () async {
              if (filePath == null) return;
              if (isPlaying) {
                await _audioPlayer?.stop();
                setState(() => _playingRecordingId = null);
              } else {
                _audioPlayer ??= AudioPlayer();
                await _audioPlayer?.stop();
                setState(() => _playingRecordingId = recId);
                try {
                  await _audioPlayer!.setUrl(filePath);
                  await _audioPlayer!.play();
                  _audioPlayer!.playerStateStream.listen((state) {
                    if (state.processingState == ProcessingState.completed && mounted) {
                      setState(() => _playingRecordingId = null);
                    }
                  });
                } catch (e) {
                  if (mounted) setState(() => _playingRecordingId = null);
                }
              }
            },
            child: Container(
              padding: const EdgeInsets.all(6),
              decoration: BoxDecoration(
                color: isPlaying ? const Color(0xFFEA5455) : const Color(0xFF7367F0),
                shape: BoxShape.circle,
              ),
              child: Icon(isPlaying ? Icons.stop_rounded : Icons.play_arrow_rounded, color: Colors.white, size: 14),
            ),
          ),
          const SizedBox(width: 6),
          // Delete button
          GestureDetector(
            onTap: () => _confirmDeleteRecording(rec),
            child: Container(
              padding: const EdgeInsets.all(6),
              decoration: BoxDecoration(
                color: const Color(0xFFEA5455).withAlpha(20),
                shape: BoxShape.circle,
              ),
              child: const Icon(Icons.delete_outline_rounded, color: Color(0xFFEA5455), size: 14),
            ),
          ),
        ],
      ),
    );
  }

  void _confirmDeleteRecording(Map<String, dynamic> rec) {
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
        title: Text("Supprimer la voix ?", style: GoogleFonts.outfit(fontWeight: FontWeight.bold)),
        content: Text("Cette action est irréversible.", style: GoogleFonts.outfit()),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(ctx),
            child: Text("Annuler", style: GoogleFonts.outfit(color: Colors.grey)),
          ),
          ElevatedButton(
            style: ElevatedButton.styleFrom(backgroundColor: const Color(0xFFEA5455), foregroundColor: Colors.white, shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12))),
            onPressed: () async {
              Navigator.pop(ctx);
              try {
                await Supabase.instance.client.from('enregistrements').delete().eq('id', rec['id']);
                if (mounted) {
                  ScaffoldMessenger.of(context).showSnackBar(
                    SnackBar(content: Text("Enregistrement supprimé", style: GoogleFonts.outfit(color: Colors.white)), backgroundColor: const Color(0xFFEA5455), behavior: SnackBarBehavior.floating, shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12))),
                  );
                  _fetchData();
                }
              } catch (e) {
                if (mounted) ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text("Erreur: $e"), backgroundColor: Colors.red));
              }
            },
            child: Text("Supprimer", style: GoogleFonts.outfit(fontWeight: FontWeight.bold)),
          ),
        ],
      ),
    );
  }

  void _showLyrics(String title, String lyrics) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (context) => Container(
        height: MediaQuery.of(context).size.height * 0.7,
        decoration: const BoxDecoration(color: Colors.white, borderRadius: BorderRadius.vertical(top: Radius.circular(30))),
        padding: const EdgeInsets.all(30),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Expanded(child: Text(title, style: GoogleFonts.outfit(fontSize: 20, fontWeight: FontWeight.bold))),
                IconButton(icon: const Icon(Icons.close), onPressed: () => Navigator.pop(context)),
              ],
            ),
            const Divider(),
            Expanded(
              child: SingleChildScrollView(
                child: Html(
                  data: lyrics,
                  style: {
                    "body": Style(
                      fontSize: FontSize(15.0),
                      color: const Color(0xFF444050),
                      lineHeight: LineHeight.number(1.6),
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
          ],
        ),
      ),
    );
  }

  void _launchResource(Map<String, dynamic> res, [String? rawLyrics]) {
    String? lyrics = rawLyrics;
    if (res['type'] == 'partition') {
      Navigator.push(
        context,
        MaterialPageRoute(
          builder: (context) => PdfViewerScreen(
            title: res['label'] ?? "Partition",
            url: res['file_path'],
          ),
        ),
      );
    } else {
      MediaModal.show(
        context,
        title: res['label'] ?? "Média",
        url: res['file_path'],
        type: res['type'] ?? 'video',
        lyrics: lyrics,
      );
    }
  }

}
