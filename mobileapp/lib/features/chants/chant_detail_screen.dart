import 'dart:io';
import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:supabase_flutter/supabase_flutter.dart';
import 'package:choralia/shared/widgets/media_modal.dart';
import 'package:choralia/shared/widgets/pdf_viewer_screen.dart';
import 'package:choralia/shared/widgets/pdf_viewer_screen.dart';
import 'package:intl/intl.dart';
import 'package:just_audio/just_audio.dart' as ja;
import 'package:flutter_html/flutter_html.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import '../../services/recording_service.dart';
import '../../services/profile_service.dart';
import '../../services/download_service.dart';

class ChantDetailScreen extends StatefulWidget {
  final dynamic chant;
  const ChantDetailScreen({super.key, required this.chant});

  @override
  State<ChantDetailScreen> createState() => _ChantDetailScreenState();
}

class _ChantDetailScreenState extends State<ChantDetailScreen> {
  final _supabase = Supabase.instance.client;
  final _audioPlayer = ja.AudioPlayer();
  bool _isPlaying = false;
  Duration _duration = Duration.zero;
  Duration _position = Duration.zero;
  List<dynamic> _partitions = [];
  List<dynamic> _audioFiles = [];
  List<dynamic> _videos = [];
  String? _currentAudioUrl;
  String? _activePartitionId;

  // Personal recordings
  final _jaPlayer = ja.AudioPlayer();
  final _recordingService = RecordingService();
  List<dynamic> _personalRecordings = [];
  String? _playingRecordingId;
  bool _isLoadingRecordings = false;
  Map<String, bool> _downloadingStatus = {}; // To track progress per URL

  @override
  void initState() {
    super.initState();
    _fetchResources();

    _audioPlayer.playerStateStream.listen((state) {
      if (mounted) setState(() => _isPlaying = state.playing);
    });
    _audioPlayer.durationStream.listen((newDuration) {
      if (mounted) setState(() => _duration = newDuration ?? Duration.zero);
    });
    _audioPlayer.positionStream.listen((newPosition) {
      if (mounted) setState(() => _position = newPosition);
    });
  }

  @override
  void dispose() {
    _audioPlayer.dispose();
    _jaPlayer.dispose();
    super.dispose();
  }

  Future<void> _fetchResources() async {
    _fetchPersonalRecordings();
    try {
      final data = await _supabase
          .from('fichier_chants')
          .select('id, type, file_path, pupitre_id, pupitres(name)')
          .eq('chant_id', widget.chant['id']);

      if (mounted) {
        setState(() {
          // Partitions
          _partitions = [];
          if (widget.chant['file_path'] != null) {
            _partitions.add({
              'id': 'main',
              'file_path': widget.chant['file_path'],
              'label': 'Partition Principale',
            });
            _activePartitionId = 'main';
          }
          
          final fetchedPartitions = data.where((r) => r['type'] == 'partition').toList();
          for (var p in fetchedPartitions) {
            _partitions.add({
              'id': p['id'].toString(),
              'file_path': p['file_path'],
              'label': 'Partition ${p['pupitres']?['name'] ?? "Générale"}',
            });
          }
          
          if (_activePartitionId == null && _partitions.isNotEmpty) {
            _activePartitionId = _partitions.first['id'];
          }

          // Audio
          _audioFiles = data.where((r) => r['type'] == 'audio').toList();
          if (_audioFiles.isNotEmpty) {
            _currentAudioUrl = _audioFiles.first['file_path'];
          }

          // Videos & YouTube
          _videos = data.where((r) => r['type'] == 'video' || r['type'] == 'youtube' || r['type'] == 'lien_youtube').toList();
        });
      }
    } catch (e) {
      if (mounted) setState(() {});
    }
  }

  Future<void> _fetchPersonalRecordings() async {
    final user = _supabase.auth.currentUser;
    if (user == null) return;
    
    if (mounted) setState(() => _isLoadingRecordings = true);
    try {
      final profileService = ProfileService();
      final internalUserId = await profileService.getIntegerUserId();

      if (internalUserId == null) {
        if (mounted) setState(() => _isLoadingRecordings = false);
        return;
      }

      final data = await _supabase
          .from('enregistrements')
          .select()
          .eq('chant_id', widget.chant['id'])
          .eq('user_id', internalUserId);
          
      if (mounted) {
        setState(() {
          _personalRecordings = data;
          _isLoadingRecordings = false;
        });
      }
    } catch (e) {
      debugPrint("ChantDetail: Error fetching recordings: $e");
      if (mounted) setState(() => _isLoadingRecordings = false);
    }
  }

  Future<void> _togglePlay() async {
    if (_currentAudioUrl == null) return;
    
    // Ensure absolute URL
    String finalUrl = _currentAudioUrl!;
    if (!finalUrl.startsWith('http')) {
      final baseUrl = dotenv.env['BACKEND_URL'] ?? "https://romero-38dc.onrender.com/";
      finalUrl = "$baseUrl/$finalUrl";
    }

    // Fix for iOS: Percent-encode special characters in the URL
    finalUrl = Uri.encodeFull(finalUrl);
    debugPrint("ChantDetail: Loading audio from URL: $finalUrl");

    if (_isPlaying) {
      await _audioPlayer.pause();
    } else {
      try {
        await _jaPlayer.stop(); // Stop potential personal recording
        setState(() => _playingRecordingId = null);
        
        await _audioPlayer.setUrl(finalUrl);
        await _audioPlayer.play();
      } catch (e) {
        debugPrint("ChantDetail: Error playing audio: $e");
        if (mounted) {
          String message = "Erreur de lecture : ${e.toString()}";
          
          if (Platform.isIOS) {
            if (e.toString().contains("-11828") || finalUrl.endsWith(".webm")) {
              message = "Format non supporté sur iPhone (.webm). Veuillez utiliser un fichier MP3 ou M4A.";
            }
          }
          
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text(message), backgroundColor: Colors.red),
          );
        }
      }
    }
  }

  Future<void> _handleDownload(String? rawUrl, String label) async {
    if (rawUrl == null) return;

    // 1. Prepare final URL
    String finalUrl = rawUrl;
    if (!finalUrl.startsWith('http')) {
      final baseUrl = dotenv.env['BACKEND_URL'] ?? "https://romero-38dc.onrender.com";
      finalUrl = "$baseUrl/$finalUrl".replaceAll(RegExp(r'(?<!:)/+'), '/');
    }
    finalUrl = Uri.encodeFull(finalUrl);

    // 2. Start download
    setState(() => _downloadingStatus[rawUrl] = true);
    try {
      final downloadService = DownloadService();
      final result = await downloadService.downloadFile(finalUrl);
      
      if (mounted && result != null) {
        final String location = result['locationDescription'] ?? "votre téléphone";
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Column(
              mainAxisSize: MainAxisSize.min,
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text("Téléchargement réussi : $label", style: const TextStyle(fontWeight: FontWeight.bold)),
                Text("Emplacement : $location", style: const TextStyle(fontSize: 12)),
              ],
            ),
            backgroundColor: const Color(0xFF28C76F),
            behavior: SnackBarBehavior.floating,
            duration: const Duration(seconds: 5),
            action: SnackBarAction(
              label: "OK",
              textColor: Colors.white,
              onPressed: () {},
            ),
          ),
        );
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text("Erreur de téléchargement : $e"),
            backgroundColor: Colors.red,
            behavior: SnackBarBehavior.floating,
          ),
        );
      }
    } finally {
      if (mounted) setState(() => _downloadingStatus[rawUrl] = false);
    }
  }

  String _formatDuration(Duration duration) {
    String twoDigits(int n) => n.toString().padLeft(2, "0");
    return "${twoDigits(duration.inMinutes.remainder(60))}:${twoDigits(duration.inSeconds.remainder(60))}";
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      body: CustomScrollView(
        slivers: [
          _buildAppBar(),
          SliverToBoxAdapter(
            child: Padding(
              padding: const EdgeInsets.symmetric(horizontal: 25),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const SizedBox(height: 20),
                  _buildHeader(),
                  const SizedBox(height: 30),
                  if (_audioFiles.isNotEmpty) _buildPremiumPlayer(),
                  const SizedBox(height: 30),
                  _buildPersonalRecordingsSection(),
                  const SizedBox(height: 40),
                  if (_videos.isNotEmpty) _buildVideoSection(),
                  const SizedBox(height: 40),
                  _buildLyricsSection(),
                  const SizedBox(height: 40),
                  if (_partitions.isNotEmpty) _buildPartitionSection(),
                  const SizedBox(height: 100),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildAppBar() {
    return SliverAppBar(
      backgroundColor: Colors.white,
      elevation: 0,
      pinned: true,
      leading: IconButton(
        icon: const Icon(Icons.arrow_back_ios_new_rounded, color: Color(0xFF444050)),
        onPressed: () => Navigator.pop(context),
      ),
    );
  }

  Widget _buildHeader() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Expanded(
              child: Text(
                widget.chant['title'] ?? 'Sans titre',
                style: GoogleFonts.outfit(fontSize: 22, fontWeight: FontWeight.bold, color: const Color(0xFF444050)),
              ),
            ),
            Container(
              padding: const EdgeInsets.all(8),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(12),
                boxShadow: [BoxShadow(color: Colors.black.withAlpha(5), blurRadius: 10)],
              ),
              child: const Icon(Icons.auto_awesome_rounded, color: Color(0xFF7367F0), size: 18),
            ),
          ],
        ),
        const SizedBox(height: 10),
        Row(
          children: [
            if (widget.chant['composer'] != null)
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                decoration: BoxDecoration(
                  color: Colors.white,
                  border: Border.all(color: const Color(0xFF7367F0).withAlpha(40)),
                  borderRadius: BorderRadius.circular(8),
                ),
                child: Text(
                  widget.chant['composer'],
                  style: GoogleFonts.outfit(fontSize: 12, color: const Color(0xFF7367F0), fontWeight: FontWeight.bold),
                ),
              ),
            const SizedBox(width: 10),
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
              decoration: BoxDecoration(
                color: const Color(0xFF28C76F).withAlpha(10),
                borderRadius: BorderRadius.circular(8),
              ),
              child: Row(
                children: [
                  Container(width: 6, height: 6, decoration: const BoxDecoration(color: Color(0xFF28C76F), shape: BoxShape.circle)),
                  const SizedBox(width: 6),
                  Text(
                    "Apprentissage Actif",
                    style: GoogleFonts.outfit(fontSize: 10, color: const Color(0xFF28C76F), fontWeight: FontWeight.bold, letterSpacing: 0.5),
                  ),
                ],
              ),
            ),
          ],
        ),
      ],
    );
  }

  Widget _buildPartitionSection() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          children: [
            const Icon(Icons.description_rounded, color: Color(0xFF444050), size: 20),
            const SizedBox(width: 8),
            Text("Partitions", style: GoogleFonts.outfit(fontSize: 18, fontWeight: FontWeight.bold, color: const Color(0xFF444050))),
          ],
        ),
        const SizedBox(height: 15),
        SingleChildScrollView(
          scrollDirection: Axis.horizontal,
          child: Row(
            children: _partitions.map((p) {
              final isSelected = _activePartitionId == p['id'];
              return Padding(
                padding: const EdgeInsets.only(right: 10),
                child: ChoiceChip(
                  label: Text(p['label']),
                  selected: isSelected,
                  onSelected: (val) {
                    if (val) setState(() => _activePartitionId = p['id']);
                  },
                  selectedColor: const Color(0xFF7367F0).withAlpha(40),
                  labelStyle: GoogleFonts.outfit(
                    color: isSelected ? const Color(0xFF7367F0) : Colors.grey[600],
                    fontWeight: FontWeight.bold,
                    fontSize: 13,
                  ),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                  backgroundColor: Colors.grey[100],
                  padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                ),
              );
            }).toList(),
          ),
        ),
        const SizedBox(height: 15),
        if (_activePartitionId != null)
          Container(
            width: double.infinity,
            padding: const EdgeInsets.all(20),
            decoration: BoxDecoration(
              color: const Color(0xFFF1F0FF),
              borderRadius: BorderRadius.circular(20),
              border: Border.all(color: const Color(0xFF7367F0).withAlpha(30)),
            ),
            child: Column(
              children: [
                const Icon(Icons.picture_as_pdf_rounded, color: Color(0xFFEA5455), size: 40),
                const SizedBox(height: 12),
                Text(
                  _partitions.firstWhere((p) => p['id'] == _activePartitionId)['label'],
                  style: GoogleFonts.outfit(fontWeight: FontWeight.bold, color: const Color(0xFF444050)),
                ),
                const SizedBox(height: 15),
                ElevatedButton.icon(
                  onPressed: () {
                    final p = _partitions.firstWhere((p) => p['id'] == _activePartitionId);
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (context) => PdfViewerScreen(
                          title: p['label'],
                          url: p['file_path'],
                        ),
                      ),
                    );
                  },
                  icon: const Icon(Icons.remove_red_eye_rounded, size: 18),
                  label: const Text("Visualiser la partition"),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: const Color(0xFF7367F0),
                    foregroundColor: Colors.white,
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                    elevation: 0,
                  ),
                ),
                const SizedBox(height: 10),
                TextButton.icon(
                  onPressed: _downloadingStatus[_partitions.firstWhere((p) => p['id'] == _activePartitionId)['file_path']] == true
                      ? null
                      : () {
                          final p = _partitions.firstWhere((p) => p['id'] == _activePartitionId);
                          _handleDownload(p['file_path'], p['label']);
                        },
                  icon: _downloadingStatus[_partitions.firstWhere((p) => p['id'] == _activePartitionId)['file_path']] == true
                      ? const SizedBox(width: 18, height: 18, child: CircularProgressIndicator(strokeWidth: 2, color: Color(0xFF7367F0)))
                      : const Icon(Icons.download_rounded, size: 18),
                  label: const Text("Télécharger la partition"),
                  style: TextButton.styleFrom(
                    foregroundColor: const Color(0xFF7367F0),
                  ),
                ),
              ],
            ),
          ),
      ],
    );
  }

  Widget _buildVideoSection() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          children: [
            const Icon(Icons.play_circle_fill_rounded, color: Color(0xFF444050), size: 20),
            const SizedBox(width: 8),
            Text("Vidéos & Liens YouTube", style: GoogleFonts.outfit(fontSize: 18, fontWeight: FontWeight.bold, color: const Color(0xFF444050))),
          ],
        ),
        const SizedBox(height: 15),
        ..._videos.map((v) {
          final bool isYoutube = v['type'] == 'youtube' || v['type'] == 'lien_youtube';
          return Container(
            margin: const EdgeInsets.only(bottom: 10),
            decoration: BoxDecoration(
              color: isYoutube ? const Color(0xFFEA5455).withAlpha(10) : const Color(0xFF7367F0).withAlpha(10),
              borderRadius: BorderRadius.circular(15),
            ),
            child: ListTile(
              leading: Icon(
                isYoutube ? Icons.play_circle_filled_rounded : Icons.video_library_rounded,
                color: isYoutube ? const Color(0xFFEA5455) : const Color(0xFF7367F0),
              ),
              title: Text(
                '${v['pupitres']?['name'] ?? "Général"} - ${isYoutube ? "YouTube" : "Vidéo"}',
                style: GoogleFonts.outfit(fontWeight: FontWeight.bold, fontSize: 14),
              ),
              trailing: const Icon(Icons.open_in_new_rounded, size: 18),
              onTap: () {
                MediaModal.show(
                  context,
                  title: '${v['pupitres']?['name'] ?? "Général"} - ${isYoutube ? "YouTube" : "Vidéo"}',
                  url: v['file_path'],
                  type: isYoutube ? 'youtube' : 'video',
                  lyrics: widget.chant['parole'], // Raw HTML
                );
              },
            ),
          );
        }),
      ],
    );
  }

  Widget _buildPremiumPlayer() {
    return Container(
      padding: const EdgeInsets.all(25),
      decoration: BoxDecoration(
        color: const Color(0xFFFAFAFE),
        borderRadius: BorderRadius.circular(30),
        boxShadow: [BoxShadow(color: Colors.black.withAlpha(5), blurRadius: 20, offset: const Offset(0, 10))],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              const Icon(Icons.mic_none_rounded, color: Color(0xFF444050), size: 20),
              const SizedBox(width: 8),
              Text("Voix de Travail", style: GoogleFonts.outfit(fontSize: 18, fontWeight: FontWeight.bold, color: const Color(0xFF444050))),
            ],
          ),
          const SizedBox(height: 20),
          SliderTheme(
            data: SliderTheme.of(context).copyWith(
              trackHeight: 4,
              thumbShape: const RoundSliderThumbShape(enabledThumbRadius: 6),
              activeTrackColor: const Color(0xFF7367F0),
              inactiveTrackColor: const Color(0xFF7367F0).withAlpha(30),
              thumbColor: const Color(0xFF7367F0),
            ),
            child: Slider(
              min: 0,
              max: _duration.inSeconds.toDouble(),
              value: _position.inSeconds.toDouble().clamp(0, _duration.inSeconds.toDouble()),
              onChanged: (value) => _audioPlayer.seek(Duration(seconds: value.toInt())),
            ),
          ),
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 10),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text(_formatDuration(_position), style: GoogleFonts.outfit(color: Colors.grey[500], fontSize: 11, fontWeight: FontWeight.bold)),
                Text(_formatDuration(_duration), style: GoogleFonts.outfit(color: Colors.grey[500], fontSize: 11, fontWeight: FontWeight.bold)),
              ],
            ),
          ),
          const SizedBox(height: 15),
          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              IconButton(icon: const Icon(Icons.replay_5_rounded, color: Color(0xFF7367F0)), onPressed: () => _audioPlayer.seek(_position - const Duration(seconds: 5))),
              const SizedBox(width: 20),
              GestureDetector(
                onTap: _togglePlay,
                child: Container(
                  height: 60,
                  width: 60,
                  decoration: BoxDecoration(
                    color: const Color(0xFF7367F0),
                    shape: BoxShape.circle,
                    boxShadow: [BoxShadow(color: const Color(0xFF7367F0).withAlpha(60), blurRadius: 15, offset: const Offset(0, 8))],
                  ),
                  child: Icon(_isPlaying ? Icons.pause_rounded : Icons.play_arrow_rounded, color: Colors.white, size: 35),
                ),
              ),
              const SizedBox(width: 20),
              IconButton(icon: const Icon(Icons.forward_5_rounded, color: Color(0xFF7367F0)), onPressed: () => _audioPlayer.seek(_position + const Duration(seconds: 5))),
              const SizedBox(width: 10),
               IconButton(
                icon: _downloadingStatus[_currentAudioUrl] == true
                    ? const SizedBox(width: 20, height: 20, child: CircularProgressIndicator(strokeWidth: 2, color: Color(0xFF7367F0)))
                    : const Icon(Icons.download_for_offline_rounded, color: Color(0xFF7367F0)),
                onPressed: _currentAudioUrl != null && _downloadingStatus[_currentAudioUrl] != true
                    ? () {
                        dynamic currentFile;
                        try {
                          currentFile = _audioFiles.firstWhere((a) => a['file_path'] == _currentAudioUrl);
                        } catch (_) {
                          currentFile = null;
                        }
                        final label = currentFile != null ? "Audio ${currentFile['pupitres']?['name'] ?? 'Tutti'}" : "Audio";
                        _handleDownload(_currentAudioUrl, label);
                      }
                    : null,
              ),
            ],
          ),
          if (_audioFiles.length > 1) ...[
            const SizedBox(height: 25),
            Wrap(
              spacing: 8,
              runSpacing: 8,
              children: _audioFiles.map((a) {
                final isCurrent = _currentAudioUrl == a['file_path'];
                final String pupitreName = a['pupitres']?['name'] ?? "Tutti";
                return InkWell(
                    onTap: () async {
                      setState(() {
                        _currentAudioUrl = a['file_path'];
                      });
                      await _audioPlayer.stop();
                    },
                  child: Container(
                    padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                    decoration: BoxDecoration(
                      color: isCurrent ? const Color(0xFF7367F0) : Colors.white,
                      borderRadius: BorderRadius.circular(10),
                      border: Border.all(color: isCurrent ? const Color(0xFF7367F0) : Colors.grey.shade200),
                    ),
                    child: Text(
                      pupitreName,
                      style: GoogleFonts.outfit(
                        color: isCurrent ? Colors.white : Colors.grey[600],
                        fontWeight: FontWeight.bold,
                        fontSize: 12,
                      ),
                    ),
                  ),
                );
              }).toList(),
            ),
          ],
        ],
      ),
    );
  }

  Widget _buildLyricsSection() {
    final lyrics = widget.chant['parole'];
    if (lyrics == null || lyrics.isEmpty) return const SizedBox.shrink();

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          children: [
            const Icon(Icons.format_quote_rounded, color: Color(0xFF444050), size: 20),
            const SizedBox(width: 8),
            Text("Paroles", style: GoogleFonts.outfit(fontSize: 18, fontWeight: FontWeight.bold, color: const Color(0xFF444050))),
          ],
        ),
        const SizedBox(height: 15),
        Container(
          width: double.infinity,
          padding: const EdgeInsets.symmetric(vertical: 40, horizontal: 25),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(35),
            boxShadow: [
              BoxShadow(
                color: const Color(0xFF7367F0).withAlpha(8),
                blurRadius: 30,
                offset: const Offset(0, 15),
              ),
            ],
          ),
          child: Column(
            children: [
              Container(
                width: 40,
                height: 2,
                color: const Color(0xFF7367F0).withAlpha(50),
              ),
              const SizedBox(height: 30),
              Html(
                data: lyrics,
                style: {
                  "body": Style(
                    fontSize: FontSize(16.0),
                    color: const Color(0xFF444050),
                    lineHeight: LineHeight.number(1.3),
                    fontStyle: FontStyle.italic,
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
            ],
          ),
        ),
      ],
    );
  }

  Widget _buildPersonalRecordingsSection() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Row(
              children: [
                const Icon(Icons.mic_rounded, color: Color(0xFF444050), size: 20),
                const SizedBox(width: 8),
                Text("Mes Voix Déposées", style: GoogleFonts.outfit(fontSize: 18, fontWeight: FontWeight.bold, color: const Color(0xFF444050))),
              ],
            ),
            if (_isLoadingRecordings) 
              const SizedBox(width: 15, height: 15, child: CircularProgressIndicator(strokeWidth: 2, color: Color(0xFF7367F0))),
          ],
        ),
        const SizedBox(height: 15),
        if (_personalRecordings.isEmpty && !_isLoadingRecordings)
          Container(
            padding: const EdgeInsets.all(20),
            decoration: BoxDecoration(
              color: const Color(0xFFF8F7FA),
              borderRadius: BorderRadius.circular(15),
            ),
            child: Row(
              children: [
                Icon(Icons.info_outline_rounded, color: Colors.blueGrey[300], size: 20),
                const SizedBox(width: 12),
                Expanded(
                  child: Text(
                    "Vous n'avez pas encore déposé de voix pour ce chant.",
                    style: GoogleFonts.outfit(color: Colors.blueGrey[400], fontSize: 13),
                  ),
                ),
              ],
            ),
          )
        else
          ..._personalRecordings.map((rec) => _buildRecordingItem(rec)),
      ],
    );
  }

  Widget _buildRecordingItem(Map<String, dynamic> rec) {
    final String recId = rec['id'].toString();
    final String? filePath = rec['file_path'];
    final bool isPlaying = _playingRecordingId == recId;

    return Container(
      margin: const EdgeInsets.only(bottom: 10),
      padding: const EdgeInsets.symmetric(horizontal: 15, vertical: 12),
      decoration: BoxDecoration(
        color: const Color(0xFF7367F0).withAlpha(10),
        borderRadius: BorderRadius.circular(15),
        border: Border.all(color: const Color(0xFF7367F0).withAlpha(20)),
      ),
      child: Row(
        children: [
          const Icon(Icons.audiotrack_rounded, color: Color(0xFF7367F0), size: 18),
          const SizedBox(width: 12),
          Expanded(
            child: Text(
              "Enregistrement du ${rec['created_at'] != null ? DateFormat('dd/MM/yyyy').format(DateTime.parse(rec['created_at'])) : 'récent'}",
              style: GoogleFonts.outfit(fontWeight: FontWeight.bold, fontSize: 13, color: const Color(0xFF444050)),
            ),
          ),
          GestureDetector(
            onTap: () async {
              if (filePath == null) return;
              
              // Ensure absolute URL
              String finalUrl = filePath;
              if (!finalUrl.startsWith('http')) {
                final baseUrl = dotenv.env['BACKEND_URL'] ?? "https://chorale.onrender.com";
                finalUrl = "$baseUrl/$finalUrl";
              }

              if (isPlaying) {
                await _jaPlayer.stop();
                setState(() => _playingRecordingId = null);
              } else {
                await _audioPlayer.stop(); // Stop main player
                setState(() => _playingRecordingId = recId);
                try {
                  await _jaPlayer.setUrl(finalUrl);
                  await _jaPlayer.play();
                  _jaPlayer.playerStateStream.listen((state) {
                    if (state.processingState == ja.ProcessingState.completed && mounted) {
                      setState(() => _playingRecordingId = null);
                    }
                  });
                } catch (e) {
                  if (mounted) setState(() => _playingRecordingId = null);
                }
              }
            },
            child: Container(
              padding: const EdgeInsets.all(8),
              decoration: BoxDecoration(
                color: isPlaying ? const Color(0xFFEA5455) : const Color(0xFF7367F0),
                shape: BoxShape.circle,
              ),
              child: Icon(isPlaying ? Icons.stop_rounded : Icons.play_arrow_rounded, color: Colors.white, size: 16),
            ),
          ),
          const SizedBox(width: 10),
          GestureDetector(
            onTap: () => _confirmDeleteRecording(rec),
            child: Container(
              padding: const EdgeInsets.all(8),
              decoration: BoxDecoration(
                color: const Color(0xFFEA5455).withAlpha(20),
                shape: BoxShape.circle,
              ),
              child: const Icon(Icons.delete_outline_rounded, color: Color(0xFFEA5455), size: 16),
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
              if (mounted) setState(() => _isLoadingRecordings = true);
              try {
                await _supabase.from('enregistrements').delete().eq('id', rec['id']);
                _fetchPersonalRecordings();
                if (mounted) {
                  ScaffoldMessenger.of(context).showSnackBar(
                    SnackBar(content: Text("Supprimé", style: GoogleFonts.outfit(color: Colors.white)), backgroundColor: const Color(0xFFEA5455), behavior: SnackBarBehavior.floating),
                  );
                }
              } catch (e) {
                if (mounted) {
                  setState(() => _isLoadingRecordings = false);
                  ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text("Erreur: $e"), backgroundColor: Colors.red));
                }
              }
            },
            child: Text("Supprimer", style: GoogleFonts.outfit(fontWeight: FontWeight.bold)),
          ),
        ],
      ),
    );
  }
}

