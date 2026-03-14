// lib/features/repetitions/repetitions_list_screen.dart
import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../services/repetition_service.dart';
import '../../models/repetition.dart';
import 'package:intl/intl.dart';
import 'carnet_de_chants_screen.dart';

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
              style: GoogleFonts.outfit(fontWeight: FontWeight.bold, fontSize: 12),
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

                final allRepetitions = snapshot.data!;
                final now = DateTime.now();
                
                // Today and future
                final upcoming = allRepetitions.where((r) => 
                  r.date.isAfter(now.subtract(const Duration(hours: 12)))
                ).toList();
                
                // Past
                final past = allRepetitions.where((r) => 
                  r.date.isBefore(now.subtract(const Duration(hours: 12)))
                ).toList();

                // Sort upcoming by nearest first
                upcoming.sort((a, b) => a.date.compareTo(b.date));
                
                // Sort past by most recent first
                past.sort((a, b) => b.date.compareTo(a.date));

                return SliverList(
                  delegate: SliverChildListDelegate([
                    if (upcoming.isNotEmpty) ...[
                      _buildSectionHeader("À VENIR"),
                      ...upcoming.map((r) => _buildPremiumRepetitionCard(r)),
                    ],
                    if (past.isNotEmpty) ...[
                      const SizedBox(height: 30),
                      _buildSectionHeader("PASSÉES"),
                      ...past.map((r) => _buildPremiumRepetitionCard(r, isPast: true)),
                    ],
                    const SizedBox(height: 50),
                  ]),
                );
              },
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildSectionHeader(String title) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 15, top: 10),
      child: Row(
        children: [
          Container(
            height: 4,
            width: 30,
            decoration: BoxDecoration(
              color: const Color(0xFF7367F0),
              borderRadius: BorderRadius.circular(2),
            ),
          ),
          const SizedBox(width: 12),
          Text(
            title,
            style: GoogleFonts.outfit(
              fontSize: 14,
              fontWeight: FontWeight.w900,
              color: const Color(0xFF444050),
              letterSpacing: 1.5,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildPremiumRepetitionCard(Repetition repetition, {bool isPast = false}) {
    final dateStr = DateFormat('EEEE dd MMMM • HH:mm', 'fr_FR').format(repetition.date);
    final accentColor = isPast ? Colors.blueGrey : const Color(0xFF7367F0);
    
    return Container(
      margin: const EdgeInsets.only(bottom: 20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(30),
        boxShadow: [
          BoxShadow(
            color: accentColor.withAlpha(10),
            blurRadius: 20,
            offset: const Offset(0, 10),
          ),
        ],
      ),
      child: Opacity(
        opacity: isPast ? 0.75 : 1.0,
        child: Material(
        color: Colors.transparent,
        child: InkWell(
          onTap: () => Navigator.push(
            context,
            MaterialPageRoute(
              builder: (context) => CarnetDeChantsScreen(
                repetitionId: repetition.id,
                repetitionTitle: repetition.title ?? 'Répétition',
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
                    if (isPast)
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                        decoration: BoxDecoration(
                          color: accentColor.withAlpha(15),
                          borderRadius: BorderRadius.circular(10),
                        ),
                        child: Text(
                          "PASSÉE",
                          style: GoogleFonts.outfit(
                            color: accentColor,
                            fontSize: 10,
                            fontWeight: FontWeight.bold,
                            letterSpacing: 1.1,
                          ),
                        ),
                      ),
                  ],
                ),
                const SizedBox(height: 20),
                Text(
                  repetition.title ?? 'Répétition sans titre',
                  style: GoogleFonts.outfit(
                    fontWeight: FontWeight.bold,
                    fontSize: 14,
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
                      child: Icon(Icons.access_time_filled_rounded, size: 16, color: accentColor),
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
                if (repetition.location != null) ...[
                  const SizedBox(height: 10),
                  Row(
                    children: [
                      Container(
                        padding: const EdgeInsets.all(8),
                        decoration: BoxDecoration(color: const Color(0xFFF8F7FA), borderRadius: BorderRadius.circular(10)),
                        child: Icon(Icons.location_on_rounded, size: 16, color: accentColor),
                      ),
                      const SizedBox(width: 12),
                      Expanded(
                        child: Text(
                          repetition.location!,
                          style: GoogleFonts.outfit(color: Colors.blueGrey[400], fontSize: 13, fontWeight: FontWeight.w500),
                        ),
                      ),
                    ],
                  ),
                ],
                const SizedBox(height: 15),
                if (!isPast) ...[
                  const Divider(height: 1),
                  const SizedBox(height: 15),
                  Text(
                    "Seriez-vous présent ?",
                    style: GoogleFonts.outfit(fontSize: 12, fontWeight: FontWeight.bold, color: Colors.blueGrey.shade600),
                  ),
                  const SizedBox(height: 10),
                  Row(
                    children: [
                      _buildPresenceChip(repetition, "Oui", "oui", Colors.green),
                      const SizedBox(width: 8),
                      _buildPresenceChip(repetition, "Non", "non", Colors.red),
                      const SizedBox(width: 8),
                      _buildPresenceChip(repetition, "Peut-être", "peut-etre", Colors.orange),
                    ],
                  ),
                ],
                const SizedBox(height: 25),
                Container(
                  width: double.infinity,
                  padding: const EdgeInsets.symmetric(vertical: 12),
                  decoration: BoxDecoration(
                    color: accentColor,
                    borderRadius: BorderRadius.circular(15),
                    boxShadow: [
                      BoxShadow(
                        color: accentColor.withAlpha(60),
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
    ),
  );
}

  Widget _buildPresenceChip(Repetition repetition, String label, String value, Color color) {
    final bool isSelected = repetition.userChoice == value;
    
    return GestureDetector(
      onTap: () async {
        if (isSelected) return;
        
        try {
          await _repetitionService.updateSondage(repetition.id, value);
          
          setState(() {
            _repetitionsFuture = _repetitionService.fetchRepetitions();
          });
          
          if (mounted) {
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(
                content: Text("Choix enregistré : $label"),
                backgroundColor: color,
                behavior: SnackBarBehavior.floating,
                duration: const Duration(seconds: 1),
              ),
            );
          }
        } catch (e) {
          if (mounted) {
            ScaffoldMessenger.of(context).showSnackBar(
              SnackBar(content: Text("Erreur: $e"), backgroundColor: Colors.red),
            );
          }
        }
      },
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
        decoration: BoxDecoration(
          color: isSelected ? color : Colors.white,
          borderRadius: BorderRadius.circular(12),
          border: Border.all(color: isSelected ? color : color.withAlpha(50)),
          boxShadow: [
            if (isSelected)
              BoxShadow(color: color.withAlpha(60), blurRadius: 8, offset: const Offset(0, 4))
            else
              BoxShadow(color: color.withAlpha(15), blurRadius: 4, offset: const Offset(0, 2)),
          ],
        ),
        child: Text(
          label,
          style: GoogleFonts.outfit(
            fontSize: 11, 
            fontWeight: FontWeight.bold, 
            color: isSelected ? Colors.white : color,
          ),
        ),
      ),
    );
  }
}
