// lib/services/audio_recorder_service.dart
import 'dart:io';
import 'package:record/record.dart';
import 'package:path_provider/path_provider.dart';
import 'package:permission_handler/permission_handler.dart';
import 'package:supabase_flutter/supabase_flutter.dart';

class AudioRecorderService {
  final AudioRecorder _recorder = AudioRecorder();
  final SupabaseClient _supabase = Supabase.instance.client;

  Future<bool> hasPermission() async {
    var status = await Permission.microphone.status;
    if (status.isPermanentlyDenied) {
      await openAppSettings();
      return false;
    }
    
    status = await Permission.microphone.request();
    return status.isGranted;
  }

  Future<void> startRecording() async {
    if (await _recorder.isRecording()) return;
    if (!await hasPermission()) throw Exception("Permission micro refusée");

    final directory = await getTemporaryDirectory();
    final path = '${directory.path}/recording_${DateTime.now().millisecondsSinceEpoch}.m4a';
    
    await _recorder.start(
      const RecordConfig(
        encoder: AudioEncoder.aacLc,
        bitRate: 128000,
        sampleRate: 44100,
      ), 
      path: path,
    );
  }

  Future<String?> stopRecording() async {
    final path = await _recorder.stop();
    return path;
  }

  Future<void> saveToSupabase(String localPath) async {
    final user = _supabase.auth.currentUser;
    if (user == null) throw Exception("Utilisateur non connecté");

    final file = File(localPath);
    final fileName = 'recordings/${user.id}/${DateTime.now().millisecondsSinceEpoch}.m4a';
    
    await _supabase.storage.from('imgs').upload(
      fileName,
      file,
      fileOptions: const FileOptions(cacheControl: '3600', upsert: false),
    );
  }

  void dispose() {
    _recorder.dispose();
  }
}
