import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:supabase_flutter/supabase_flutter.dart';
import 'package:chorale_app_mobile/features/chants/chant_detail_screen.dart';

class ChantsScreen extends StatefulWidget {
  const ChantsScreen({super.key});

  @override
  State<ChantsScreen> createState() => _ChantsScreenState();
}

class _ChantsScreenState extends State<ChantsScreen> {
  final _supabase = Supabase.instance.client;
  bool _isLoading = true;
  List<dynamic> _chants = [];

  @override
  void initState() {
    super.initState();
    _fetchChants();
  }

  Future<void> _fetchChants() async {
    try {
      final data = await _supabase
          .from('chants')
          .select('id, title, composer, parole, file_path')
          .order('title');
      
      if (mounted) {
        setState(() {
          _chants = data;
          _isLoading = false;
        });
      }
    } catch (e) {
      if (mounted) {
        setState(() => _isLoading = false);
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text("Erreur lors de la récupération des chants")),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return CustomScrollView(
      slivers: [
        SliverAppBar(
          expandedHeight: 100,
          floating: false,
          pinned: true,
          backgroundColor: Colors.white,
          elevation: 0,
          flexibleSpace: FlexibleSpaceBar(
            titlePadding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
            title: Text(
              "Bibliothèque",
              style: GoogleFonts.outfit(fontWeight: FontWeight.bold, fontSize: 18, color: const Color(0xFF444050)),
            ),
          ),
        ),
        SliverToBoxAdapter(
          child: Padding(
            padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 10),
            child: TextField(
              decoration: InputDecoration(
                hintText: "Rechercher un chant...",
                prefixIcon: const Icon(Icons.search_rounded, color: Colors.slate),
                filled: true,
                fillColor: Colors.slate.shade50,
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(15),
                  borderSide: BorderSide.none,
                ),
                contentPadding: const EdgeInsets.symmetric(vertical: 15),
              ),
            ),
          ),
        ),
        if (_isLoading)
          const SliverFillRemaining(
            child: Center(child: CircularProgressIndicator()),
          )
        else if (_chants.isEmpty)
          const SliverFillRemaining(
            child: Center(child: Text("Aucun chant trouvé")),
          )
        else
          SliverPadding(
            padding: const EdgeInsets.all(20),
            sliver: SliverList(
              delegate: SliverChildBuilderDelegate(
                (context, index) {
                  final chant = _chants[index];
                  return _buildChantCard(chant);
                },
                childCount: _chants.length,
              ),
            ),
          ),
      ],
    );
  }

  Widget _buildChantCard(dynamic chant) {
    return GestureDetector(
      onTap: () => Navigator.push(
        context,
        MaterialPageRoute(
          builder: (context) => ChantDetailScreen(chant: chant),
        ),
      ),
      child: Container(
        margin: const EdgeInsets.bottom(15),
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(20),
          border: Border.all(color: Colors.slate.shade100),
        ),
        child: Row(
          children: [
            Container(
              width: 50,
              height: 50,
              decoration: BoxDecoration(
                color: const Color(0xFF7367F0).withOpacity(0.1),
                borderRadius: BorderRadius.circular(15),
              ),
              child: const Icon(Icons.music_note_rounded, color: Color(0xFF7367F0)),
            ),
            const SizedBox(width: 15),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    chant['title'] ?? 'Sans titre',
                    style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16, color: Color(0xFF444050)),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    chant['composer'] ?? 'Compositeur inconnu',
                    style: TextStyle(fontSize: 13, color: Colors.slate.shade400),
                  ),
                ],
              ),
            ),
            Container(
              padding: const EdgeInsets.all(8),
              decoration: BoxDecoration(
                color: const Color(0xFF7367F0),
                borderRadius: BorderRadius.circular(12),
              ),
              child: const Icon(Icons.play_arrow_rounded, color: Colors.white, size: 20),
            ),
          ],
        ),
      ),
    );
  }
}
