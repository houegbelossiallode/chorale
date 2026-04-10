import 'dart:io';
import 'package:http/http.dart' as http;
import 'package:path_provider/path_provider.dart';
import 'package:path/path.dart' as path;
import 'package:permission_handler/permission_handler.dart';
import 'package:flutter/foundation.dart';
import 'push_notification_service.dart';

class DownloadService {
  static final DownloadService _instance = DownloadService._internal();
  factory DownloadService() => _instance;
  DownloadService._internal();

  /// Downloads a file from the given [url] and saves it to the local storage.
  /// Returns a Map with 'path' and 'locationDescription'.
  Future<Map<String, String>?> downloadFile(String url, {String? fileName}) async {
    try {
      // 1. Check storage permissions (mainly for Android)
      if (Platform.isAndroid) {
        // Try requesting audio/media permissions first for Android 13+
        final audioStatus = await Permission.audio.request();
        if (!audioStatus.isGranted) {
           // Fallback to legacy storage permission for older Androids
           final storageStatus = await Permission.storage.request();
           if (!storageStatus.isGranted) {
              throw Exception("Permission de stockage ou audio refusée.");
           }
        }
      }

      // 2. Determine save directory
      Directory? directory;
      String locationDescription = "";
      
      if (Platform.isAndroid) {
        // Attempt to find the public Download folder
        directory = Directory('/storage/emulated/0/Download');
        if (!await directory.exists()) {
          directory = await getExternalStorageDirectory();
          locationDescription = "Stockage Interne > Android > data > ... > files";
        } else {
          locationDescription = "Dossier Téléchargements";
        }
      } else if (Platform.isIOS) {
        directory = await getApplicationDocumentsDirectory();
        locationDescription = "App Fichiers > Sur mon iPhone > Choralia";
      } else {
        directory = await getApplicationDocumentsDirectory();
        locationDescription = "Documents de l'application";
      }

      if (directory == null) throw Exception("Répertoire de stockage introuvable.");

      // 3. Prepare file path
      final String name = fileName ?? path.basename(Uri.parse(url).path);
      final String filePath = path.join(directory.path, name);
      final File file = File(filePath);

      // Notify start
      final pushService = PushNotificationService();
      final notificationId = url.hashCode;
      await pushService.showLocalNotification(
        id: notificationId,
        title: "Téléchargement en cours",
        body: name,
      );

      // 4. Perform download
      final response = await http.get(Uri.parse(url));
      if (response.statusCode == 200) {
        await file.writeAsBytes(response.bodyBytes);
        debugPrint("DownloadService: File saved to $filePath");
        
        // Notify success
        await pushService.showLocalNotification(
          id: notificationId,
          title: "Téléchargement terminé",
          body: "$name enregistré dans $locationDescription",
        );

        return {
          'path': filePath,
          'locationDescription': locationDescription,
        };
      } else {
        // Notify failure
        await pushService.showLocalNotification(
          id: notificationId,
          title: "Échec du téléchargement",
          body: "Code ${response.statusCode} pour $name",
        );
        throw Exception("Échec du téléchargement: Code ${response.statusCode}");
      }
    } catch (e) {
      debugPrint("DownloadService: Error: $e");
      rethrow;
    }
  }

  /// Checks if a file exists locally.
  Future<bool> isFileDownloaded(String fileName) async {
     Directory? directory;
      if (Platform.isAndroid) {
        directory = await getExternalStorageDirectory();
        if (directory == null) {
          directory = await getApplicationDocumentsDirectory();
        }
      } else {
        directory = await getApplicationDocumentsDirectory();
      }
      
      if (directory == null) return false;
      final file = File(path.join(directory.path, fileName));
      return await file.exists();
  }
}
