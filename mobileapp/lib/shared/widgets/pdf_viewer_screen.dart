import 'dart:io';
import 'package:flutter/material.dart';
import 'package:flutter_pdfview/flutter_pdfview.dart';
import 'package:path_provider/path_provider.dart';
import '../../services/laravel_service.dart';

class PdfViewerScreen extends StatefulWidget {
  final String title;
  final String url;

  const PdfViewerScreen({
    super.key,
    required this.title,
    required this.url,
  });

  @override
  State<PdfViewerScreen> createState() => _PdfViewerScreenState();
}

class _PdfViewerScreenState extends State<PdfViewerScreen> {
  String? _localPath;
  bool _isLoading = true;
  String? _error;

  @override
  void initState() {
    super.initState();
    _downloadPdf();
  }

  Future<void> _downloadPdf() async {
    try {
      final response = await LaravelService().get(widget.url);
      final bytes = response.bodyBytes;
      
      if (response.statusCode != 200) {
        throw Exception("Échec du téléchargement (Status: ${response.statusCode})");
      }

      final dir = await getTemporaryDirectory();
      final file = File('${dir.path}/temp_partition.pdf');
      await file.writeAsBytes(bytes);
      if (mounted) {
        setState(() {
          _localPath = file.path;
          _isLoading = false;
        });
      }
    } catch (e) {
      if (mounted) {
        setState(() {
          _error = e.toString();
          _isLoading = false;
        });
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(widget.title),
        actions: [
          if (_localPath != null)
            IconButton(
              icon: const Icon(Icons.share),
              onPressed: () {
                // Future improvement: share plugin
              },
            ),
        ],
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator(color: Color(0xFF7367F0)))
          : _error != null
              ? Center(child: Text("Erreur : $_error"))
              : PDFView(
                  filePath: _localPath!,
                  enableSwipe: true,
                  swipeHorizontal: false,
                  autoSpacing: false,
                  pageFling: true,
                  onRender: (pages) {
                    debugPrint("Rendered $pages pages");
                  },
                  onError: (error) {
                    setState(() => _error = error.toString());
                  },
                ),
    );
  }
}
