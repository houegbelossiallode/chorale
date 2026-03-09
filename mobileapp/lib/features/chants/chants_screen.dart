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
  List<dynamic> _filteredChants = [];
  final TextEditingController _searchController = TextEditingController();

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
          _filteredChants = data;
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

  void _filterChants(String query) {
    setState(() {
      _filteredChants = _chants
          .where((chant) =>
              (chant['title']?.toString().toLowerCase() ?? "").contains(query.toLowerCase()) ||
              (chant['composer']?.toString().toLowerCase() ?? "").contains(query.toLowerCase()))
          .toList();
    });
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
              controller: _searchController,
              onChanged: _filterChants,
              decoration: InputDecoration(
                hintText: "Rechercher un chant...",
                prefixIcon: const Icon(Icons.search_rounded, color: Colors.blueGrey),
                suffixIcon: _searchController.text.isNotEmpty
                    ? IconButton(
                        icon: const Icon(Icons.clear_rounded, size: 20),
                        onPressed: () {
                          _searchController.clear();
                          _filterChants("");
                        },
                      )
                    : null,
                filled: true,
                fillColor: Colors.blueGrey.shade50,
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
        else if (_filteredChants.isEmpty)
          const SliverFillRemaining(
            child: Center(child: Text("Aucun chant trouvé")),
          )
        else
          SliverPadding(
            padding: const EdgeInsets.all(20),
            sliver: SliverList(
              delegate: SliverChildBuilderDelegate(
                (context, index) {
                  final chant = _filteredChants[index];
                  return _buildChantCard(chant);
                },
                childCount: _filteredChants.length,
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
        margin: const EdgeInsets.only(bottom: 15.0),
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(20),
          border: Border.all(color: Colors.blueGrey.shade100),
        ),
        child: Row(
          children: [
            Container(
              width: 50,
              height: 50,
              decoration: BoxDecoration(
                color: const Color(0xFF7367F0).withAlpha(25),
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
                    style: TextStyle(fontSize: 13, color: Colors.blueGrey.shade400),
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
