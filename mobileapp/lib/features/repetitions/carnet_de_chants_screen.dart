import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:supabase_flutter/supabase_flutter.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import '../chants/chant_detail_screen.dart';
import '../../services/repetition_service.dart';
import 'package:choralia/shared/widgets/media_modal.dart';
import 'package:choralia/shared/widgets/pdf_viewer_screen.dart';
import '../../services/recording_service.dart';
import '../../services/audio_recorder_service.dart';
import 'package:just_audio/just_audio.dart';
import 'package:flutter_html/flutter_html.dart';
import 'package:permission_handler/permission_handler.dart';
import 'dart:async';

class CarnetDeChantsScreen extends StatefulWidget {
  final String repetitionId;
  final String repetitionTitle;

  const CarnetDeChantsScreen({
    super.key,
    required this.repetitionId,
    required this.repetitionTitle,
  });

  @override
  State<CarnetDeChantsScreen> createState() => _CarnetDeChantsScreenState();
}

class _CarnetDeChantsScreenState extends State<CarnetDeChantsScreen> {
  final RepetitionService _repetitionService = RepetitionService();
  final AudioRecorderService _recorderService = AudioRecorderService();
  final RecordingService _recordingService = RecordingService();

  bool _isLoading = true;
  Map<String, List<Map<String, dynamic>>> _grouped = {};

  bool _isRecording = false;
  int _recordingSeconds = 0;
  String? _currentRepertoireItemId;
  Timer? _timer;

  // Audio playback
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
    _audioPlayer?.dispose();
    _recorderService.dispose();
    super.dispose();
  }

  Future<void> _fetchData() async {
    try {
      final data = await _repetitionService.fetchRepertoireGroupedByEvent(widget.repetitionId);
      if (mounted) {
        setState(() {
          _grouped = data;
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
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text("Micro error: $e"),
            backgroundColor: Colors.red,
            action: SnackBarAction(
              label: "Paramètres",
              textColor: Colors.white,
              onPressed: () => openAppSettings(),
            ),
          ),
        );
      }
    }
  }

  void _showSaveDialog(String path, String chantId, String repertoireId) {
    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (dialogContext) => AlertDialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
        title: Text("Enregistrement terminé", style: GoogleFonts.outfit(fontWeight: FontWeight.bold)),
        content: Text("Voulez-vous sauvegarder votre voix pour ce chant ? (${_recordingSeconds}s)", style: GoogleFonts.outfit()),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(dialogContext),
            child: Text("Annuler", style: GoogleFonts.outfit(color: Colors.grey)),
          ),
          ElevatedButton(
            style: ElevatedButton.styleFrom(
              backgroundColor: const Color(0xFF7367F0),
              foregroundColor: Colors.white,
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            ),
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
                  ScaffoldMessenger.of(context).showSnackBar(
                    SnackBar(content: Text("Erreur sauvegarde: $e"), backgroundColor: Colors.red),
                  );
                }
              }
            },
            child: Text("Sauvegarder", style: GoogleFonts.outfit(fontWeight: FontWeight.bold)),
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
          SliverAppBar(
            expandedHeight: 100,
            pinned: true,
            backgroundColor: Colors.white,
            foregroundColor: const Color(0xFF444050),
            elevation: 0,
            flexibleSpace: FlexibleSpaceBar(
              titlePadding: const EdgeInsets.only(left: 60, bottom: 16),
              title: Text(
                widget.repetitionTitle,
                style: GoogleFonts.outfit(fontWeight: FontWeight.bold, fontSize: 10, color: const Color(0xFF444050)),
              ),
              background: Container(
                decoration: BoxDecoration(
                  gradient: LinearGradient(
                    colors: [const Color(0xFF7367F0).withAlpha(20), Colors.white],
                    begin: Alignment.topCenter,
                    end: Alignment.bottomCenter,
                  ),
                ),
              ),
            ),
          ),
          if (_isLoading)
            const SliverFillRemaining(child: Center(child: CircularProgressIndicator()))
          else if (_grouped.isEmpty)
            SliverFillRemaining(
              child: Center(
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    const Icon(Icons.library_music_rounded, size: 80, color: Color(0xFFD0D2D6)),
                    const SizedBox(height: 15),
                    Text("Aucun chant dans ce carnet", style: GoogleFonts.outfit(color: Colors.grey[500], fontSize: 16)),
                  ],
                ),
              ),
            )
          else
            SliverPadding(
              padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 20),
              sliver: SliverList(
                delegate: SliverChildBuilderDelegate(
                  (context, index) {
                    final eventTitle = _grouped.keys.elementAt(index);
                    final items = _grouped[eventTitle]!;
                    return _buildEventGroup(eventTitle, items);
                  },
                  childCount: _grouped.length,
                ),
              ),
            ),
        ],
      ),
    );
  }

  Widget _buildEventGroup(String eventTitle, List<Map<String, dynamic>> items) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        // Event header
        Container(
          margin: const EdgeInsets.only(bottom: 15, top: 20),
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
          decoration: BoxDecoration(
            gradient: const LinearGradient(
              colors: [Color(0xFF7367F0), Color(0xFF9E95F5)],
              begin: Alignment.centerLeft,
              end: Alignment.centerRight,
            ),
            borderRadius: BorderRadius.circular(14),
          ),
          child: Row(
            children: [
              const Icon(Icons.event_rounded, color: Colors.white, size: 18),
              const SizedBox(width: 10),
              Expanded(
                child: Text(
                  eventTitle,
                  style: GoogleFonts.outfit(fontWeight: FontWeight.bold, fontSize: 14, color: Colors.white),
                ),
              ),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                decoration: BoxDecoration(color: Colors.white.withAlpha(40), borderRadius: BorderRadius.circular(10)),
                child: Text("${items.length} chant${items.length > 1 ? 's' : ''}", style: GoogleFonts.outfit(color: Colors.white, fontSize: 11, fontWeight: FontWeight.bold)),
              ),
            ],
          ),
        ),
        ...items.map((item) => _buildChantCard(item)),
      ],
    );
  }

  Widget _buildChantCard(Map<String, dynamic> item) {
    final chant = item['chants'];
    final List<dynamic> ressources = chant?['fichier_chants'] ?? [];
    final List<dynamic> enregistrements = chant?['enregistrements'] ?? [];
    final bool isRecorded = enregistrements.isNotEmpty;
    final bool isRecordingThis = _isRecording && _currentRepertoireItemId == item['id'].toString();

    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        boxShadow: [
          BoxShadow(color: Colors.black.withAlpha(6), blurRadius: 12, offset: const Offset(0, 4)),
        ],
      ),
      child: Material(
        color: Colors.transparent,
        child: InkWell(
          onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => ChantDetailScreen(chant: chant))),
          borderRadius: BorderRadius.circular(20),
          child: Padding(
            padding: const EdgeInsets.all(16),
            child: Column(
              children: [
                Row(
                  children: [
                    Container(
                      width: 46,
                      height: 46,
                      decoration: BoxDecoration(
                        gradient: const LinearGradient(colors: [Color(0xFF7367F0), Color(0xFF9E95F5)]),
                        borderRadius: BorderRadius.circular(14),
                      ),
                      child: const Icon(Icons.music_note_rounded, color: Colors.white, size: 22),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(chant?['title'] ?? 'Sans titre', style: GoogleFonts.outfit(fontWeight: FontWeight.bold, fontSize: 13, color: const Color(0xFF444050))),
                          Row(
                            children: [
                              Text(chant?['composer'] ?? 'Compositeur inconnu', style: GoogleFonts.outfit(fontSize: 11, color: Colors.grey[500])),
                              if (item['partie_events']?['titre'] != null) ...[
                                const SizedBox(width: 8),
                                Text("•", style: TextStyle(color: Colors.grey[300], fontSize: 11)),
                                const SizedBox(width: 8),
                                Text(item['partie_events']['titre'], style: GoogleFonts.outfit(fontSize: 11, color: const Color(0xFF7367F0), fontWeight: FontWeight.bold)),
                              ],
                            ],
                          ),
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
                const SizedBox(height: 12),
                Row(
                  children: [
                    Expanded(
                      child: SingleChildScrollView(
                        scrollDirection: Axis.horizontal,
                        child: Row(
                          children: [
                            if (chant?['parole'] != null)
                              _buildPill(Icons.article_outlined, "Paroles", const Color(0xFF7367F0), () => _showLyrics(chant['title'], chant['parole'])),
                            if (chant?['file_path'] != null)
                              _buildPill(Icons.picture_as_pdf_outlined, "Partition", const Color(0xFFEA5455), () => _launchResource({'type': 'partition', 'file_path': chant['file_path'], 'label': 'Partition Principale'})),
                            ...ressources.map((r) => _buildPill(
                              r['type'] == 'audio' ? Icons.headset_rounded : r['type'] == 'youtube' ? Icons.play_circle_fill : Icons.description_outlined,
                              "${r['type'].toString().toUpperCase()}${r['pupitres']?['name'] != null ? ' (${r['pupitres']['name']})' : ''}",
                              r['type'] == 'youtube' ? const Color(0xFFEA5455) : const Color(0xFF444050),
                              () => _launchResource(r, chant?['parole']),
                            )),
                          ],
                        ),
                      ),
                    ),
                    const SizedBox(width: 10),
                    if (!isRecorded || isRecordingThis)
                      GestureDetector(
                        onTap: () => _toggleRecording(chant['id'].toString(), item['id'].toString()),
                        child: Container(
                          padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                          decoration: BoxDecoration(
                            color: isRecordingThis ? const Color(0xFFEA5455) : const Color(0xFF7367F0).withAlpha(25),
                            borderRadius: BorderRadius.circular(12),
                          ),
                          child: Row(
                            children: [
                              Icon(isRecordingThis ? Icons.stop_rounded : Icons.mic_rounded, size: 16, color: isRecordingThis ? Colors.white : const Color(0xFF7367F0)),
                              if (isRecordingThis) ...[
                                const SizedBox(width: 5),
                                Text("${_recordingSeconds}s", style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 12)),
                              ],
                            ],
                          ),
                        ),
                      ),
                  ],
                ),
              ],
            ),
          ),
        ),
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
                  String finalUrl = filePath;
                  if (!finalUrl.startsWith('http')) {
                    final baseUrl = dotenv.env['BACKEND_URL'] ?? "https://romero-38dc.onrender.com";
                    finalUrl = "$baseUrl/$finalUrl";
                  }
                  
                  // Fix for iOS: Percent-encode special characters in the URL
                  finalUrl = Uri.encodeFull(finalUrl);
                  debugPrint("CarnetDeChants: Loading recording from URL: $finalUrl");

                  await _audioPlayer!.setUrl(finalUrl);
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

  Widget _buildPill(IconData icon, String label, Color color, VoidCallback onTap) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        margin: const EdgeInsets.only(right: 8),
        padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
        decoration: BoxDecoration(color: color.withAlpha(15), borderRadius: BorderRadius.circular(10), border: Border.all(color: color.withAlpha(25))),
        child: Row(
          children: [
            Icon(icon, size: 12, color: color),
            const SizedBox(width: 5),
            Text(label, style: GoogleFonts.outfit(fontSize: 10, fontWeight: FontWeight.bold, color: color)),
          ],
        ),
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
        decoration: const BoxDecoration(color: Colors.white, borderRadius: BorderRadius.vertical(top: Radius.circular(28))),
        padding: const EdgeInsets.all(28),
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
      String finalUrl = res['file_path'];
      if (!finalUrl.startsWith('http')) {
        final baseUrl = dotenv.env['BACKEND_URL'] ?? "https://chorale.onrender.com";
        finalUrl = "$baseUrl/$finalUrl";
      }
      Navigator.push(context, MaterialPageRoute(builder: (_) => PdfViewerScreen(title: res['label'] ?? "Partition", url: finalUrl)));
    } else {
      MediaModal.show(context, title: res['label'] ?? "Média", url: res['file_path'], type: res['type'] ?? 'video', lyrics: lyrics);
    }
  }
}
