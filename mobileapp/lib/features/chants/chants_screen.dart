import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:supabase_flutter/supabase_flutter.dart';
import 'package:choralia/features/chants/chant_detail_screen.dart';

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
    return Scaffold(
      backgroundColor: const Color(0xFFFAFAFE),
      body: CustomScrollView(
        slivers: [
          _buildSliverAppBar(),
          SliverToBoxAdapter(
            child: Padding(
              padding: const EdgeInsets.fromLTRB(25, 25, 25, 10),
              child: _buildSearchBar(),
            ),
          ),
          if (_isLoading)
            const SliverFillRemaining(child: Center(child: CircularProgressIndicator()))
          else if (_filteredChants.isEmpty)
            _buildEmptyState()
          else
            _buildChantsList(),
        ],
      ),
    );
  }

  Widget _buildSliverAppBar() {
    return SliverAppBar(
      expandedHeight: 0,
      pinned: true,
      elevation: 0,
      backgroundColor: Colors.white,
      foregroundColor: const Color(0xFF444050),
      centerTitle: true,
      title: Text("Bibliothèque Musicale", style: GoogleFonts.outfit(fontWeight: FontWeight.bold, fontSize: 18)),
      actions: [
        IconButton(icon: const Icon(Icons.sort_by_alpha_rounded), onPressed: () {}),
        const SizedBox(width: 10),
      ],
    );
  }

  Widget _buildSearchBar() {
    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        boxShadow: [BoxShadow(color: Colors.black.withAlpha(5), blurRadius: 15, offset: const Offset(0, 5))],
      ),
      child: TextField(
        controller: _searchController,
        onChanged: _filterChants,
        style: GoogleFonts.outfit(fontSize: 15, color: const Color(0xFF444050)),
        decoration: InputDecoration(
          hintText: "Rechercher une mélodie...",
          hintStyle: GoogleFonts.outfit(color: Colors.blueGrey[200], fontSize: 15),
          prefixIcon: const Icon(Icons.search_rounded, color: Color(0xFF7367F0), size: 22),
          border: InputBorder.none,
          contentPadding: const EdgeInsets.symmetric(vertical: 15),
          suffixIcon: _searchController.text.isNotEmpty
              ? IconButton(icon: const Icon(Icons.close_rounded, size: 18), onPressed: () { _searchController.clear(); _filterChants(""); })
              : null,
        ),
      ),
    );
  }

  Widget _buildEmptyState() {
    return SliverFillRemaining(
      child: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Icons.search_off_rounded, size: 80, color: Colors.blueGrey[100]),
            const SizedBox(height: 15),
            Text("Aucun chant n'a été trouvé", style: GoogleFonts.outfit(color: Colors.blueGrey[300], fontSize: 16)),
          ],
        ),
      ),
    );
  }

  Widget _buildChantsList() {
    return SliverPadding(
      padding: const EdgeInsets.all(25),
      sliver: SliverList(
        delegate: SliverChildBuilderDelegate(
          (context, index) => _buildPremiumChantCard(_filteredChants[index]),
          childCount: _filteredChants.length,
        ),
      ),
    );
  }

  Widget _buildPremiumChantCard(dynamic chant) {
    return Container(
      margin: const EdgeInsets.only(bottom: 15),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(24),
        boxShadow: [BoxShadow(color: Colors.black.withAlpha(5), blurRadius: 15, offset: const Offset(0, 5))],
      ),
      child: Material(
        color: Colors.transparent,
        child: InkWell(
          onTap: () => Navigator.push(context, MaterialPageRoute(builder: (context) => ChantDetailScreen(chant: chant))),
          borderRadius: BorderRadius.circular(24),
          child: Padding(
            padding: const EdgeInsets.all(18),
            child: Row(
              children: [
                Container(
                  width: 50,
                  height: 50,
                  decoration: BoxDecoration(
                    gradient: const LinearGradient(colors: [Color(0xFF7367F0), Color(0xFF9E95F5)]),
                    borderRadius: BorderRadius.circular(16),
                  ),
                  child: const Icon(Icons.music_note_rounded, color: Colors.white, size: 24),
                ),
                const SizedBox(width: 15),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(chant['title'] ?? 'Sans titre', style: GoogleFonts.outfit(fontWeight: FontWeight.bold, fontSize: 16, color: const Color(0xFF444050), height: 1.2)),
                      const SizedBox(height: 4),
                      Text(chant['composer'] ?? 'Compositeur inconnu', style: GoogleFonts.outfit(fontSize: 13, color: Colors.blueGrey[300], fontWeight: FontWeight.w500)),
                    ],
                  ),
                ),
                Container(
                  padding: const EdgeInsets.all(8),
                  decoration: BoxDecoration(color: const Color(0xFF7367F0).withAlpha(15), shape: BoxShape.circle),
                  child: const Icon(Icons.arrow_forward_ios_rounded, color: Color(0xFF7367F0), size: 14),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
