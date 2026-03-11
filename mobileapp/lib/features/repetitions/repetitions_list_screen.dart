// lib/features/repetitions/repetitions_list_screen.dart
import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../services/repetition_service.dart';
import '../../models/repetition.dart';
import 'package:intl/intl.dart';
import '../events/repertoire_screen.dart';

class RepetitionsListScreen extends StatefulWidget {
  const RepetitionsListScreen({super.key});

  @override
  State<RepetitionsListScreen> createState() => _RepetitionsListScreenState();
}

class _RepetitionsListScreenState extends State<RepetitionsListScreen> {
  final RepetitionService _repetitionService = RepetitionService();
  late Future<List<Repetition>> _repetitionsFuture;

  @override
  void initState() {
    super.initState();
    _repetitionsFuture = _repetitionService.fetchRepetitions();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFFAFAFE),
      body: CustomScrollView(
        slivers: [
          SliverAppBar(
            expandedHeight: 0,
            pinned: true,
            backgroundColor: Colors.white,
            foregroundColor: const Color(0xFF444050),
            elevation: 0,
            centerTitle: true,
            title: Text(
              "Répétitions",
              style: GoogleFonts.outfit(fontWeight: FontWeight.bold, fontSize: 18),
            ),
          ),
          SliverPadding(
            padding: const EdgeInsets.all(25),
            sliver: FutureBuilder<List<Repetition>>(
              future: _repetitionsFuture,
              builder: (context, snapshot) {
                if (snapshot.connectionState == ConnectionState.waiting) {
                  return const SliverFillRemaining(child: Center(child: CircularProgressIndicator()));
                } else if (snapshot.hasError) {
                  return SliverFillRemaining(child: Center(child: Text("Erreur: ${snapshot.error}")));
                } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
                  return SliverFillRemaining(child: Center(child: Text("Aucune répétition enregistrée.")));
                }

                final repetitions = snapshot.data!;
                return SliverList(
                  delegate: SliverChildBuilderDelegate(
                    (context, index) => _buildPremiumRepetitionCard(repetitions[index]),
                    childCount: repetitions.length,
                  ),
                );
              },
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildPremiumRepetitionCard(Repetition repetition) {
    final dateStr = DateFormat('EEEE dd MMMM • HH:mm', 'fr_FR').format(repetition.date);
    
    return Container(
      margin: const EdgeInsets.only(bottom: 20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(30),
        boxShadow: [
          BoxShadow(
            color: const Color(0xFF28C76F).withAlpha(10),
            blurRadius: 20,
            offset: const Offset(0, 10),
          ),
        ],
      ),
      child: Material(
        color: Colors.transparent,
        child: InkWell(
          onTap: () => Navigator.push(
            context,
            MaterialPageRoute(
              builder: (context) => RepertoireScreen(
                repetitionId: repetition.id,
                title: repetition.title ?? 'Répétition',
              ),
            ),
          ),
          borderRadius: BorderRadius.circular(30),
          child: Padding(
            padding: const EdgeInsets.all(25),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                      decoration: BoxDecoration(
                        color: const Color(0xFF28C76F).withAlpha(15),
                        borderRadius: BorderRadius.circular(10),
                      ),
                      child: Text(
                        "RÉPÉTITION",
                        style: GoogleFonts.outfit(
                          color: const Color(0xFF28C76F),
                          fontSize: 10,
                          fontWeight: FontWeight.bold,
                          letterSpacing: 1.1,
                        ),
                      ),
                    ),
                    const Icon(Icons.arrow_forward_ios_rounded, color: Color(0xFFD0D2D6), size: 14),
                  ],
                ),
                const SizedBox(height: 20),
                Text(
                  repetition.title ?? 'Répétition sans titre',
                  style: GoogleFonts.outfit(
                    fontWeight: FontWeight.bold,
                    fontSize: 20,
                    color: const Color(0xFF444050),
                    height: 1.2,
                  ),
                ),
                const SizedBox(height: 15),
                Row(
                  children: [
                    Container(
                      padding: const EdgeInsets.all(8),
                      decoration: BoxDecoration(color: const Color(0xFFF8F7FA), borderRadius: BorderRadius.circular(10)),
                      child: const Icon(Icons.access_time_filled_rounded, size: 16, color: Color(0xFF28C76F)),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: Text(
                        dateStr,
                        style: GoogleFonts.outfit(color: Colors.blueGrey[400], fontSize: 13, fontWeight: FontWeight.w500),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 25),
                Container(
                  width: double.infinity,
                  padding: const EdgeInsets.symmetric(vertical: 12),
                  decoration: BoxDecoration(
                    color: const Color(0xFF28C76F),
                    borderRadius: BorderRadius.circular(15),
                    boxShadow: [
                      BoxShadow(
                        color: const Color(0xFF28C76F).withAlpha(60),
                        blurRadius: 10,
                        offset: const Offset(0, 4),
                      ),
                    ],
                  ),
                  child: Center(
                    child: Text(
                      "Ouvrir le carnet de chants",
                      style: GoogleFonts.outfit(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 14),
                    ),
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
