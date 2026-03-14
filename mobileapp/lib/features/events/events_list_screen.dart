// lib/features/events/events_list_screen.dart
import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../services/event_service.dart';
import '../../models/event.dart';
import 'package:intl/intl.dart';
import 'repertoire_screen.dart';

class EventsListScreen extends StatefulWidget {
  const EventsListScreen({super.key});

  @override
  State<EventsListScreen> createState() => _EventsListScreenState();
}

class _EventsListScreenState extends State<EventsListScreen> {
  final EventService _eventService = EventService();
  late Future<List<Event>> _eventsFuture;

  @override
  void initState() {
    super.initState();
    _eventsFuture = _eventService.fetchEvents();
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
              "Agenda & Événements",
              style: GoogleFonts.outfit(fontWeight: FontWeight.bold, fontSize: 11),
            ),
          ),
          SliverPadding(
            padding: const EdgeInsets.all(25),
            sliver: FutureBuilder<List<Event>>(
              future: _eventsFuture,
              builder: (context, snapshot) {
                if (snapshot.connectionState == ConnectionState.waiting) {
                  return const SliverFillRemaining(child: Center(child: CircularProgressIndicator()));
                } else if (snapshot.hasError) {
                  return SliverFillRemaining(child: Center(child: Text("Erreur: ${snapshot.error}")));
                } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
                  return SliverFillRemaining(child: Center(child: Text("Aucun événement prévu.")));
                }

                final allEvents = snapshot.data!;
                final now = DateTime.now();
                
                // Today and future
                final upcomingEvents = allEvents.where((e) => 
                  e.startDate.isAfter(now.subtract(const Duration(hours: 12))) // Keep today's events visible
                ).toList();
                
                // Past
                final pastEvents = allEvents.where((e) => 
                  e.startDate.isBefore(now.subtract(const Duration(hours: 12)))
                ).toList();

                // Sort upcoming by nearest first
                upcomingEvents.sort((a, b) => a.startDate.compareTo(b.startDate));
                
                // Sort past by most recent first
                pastEvents.sort((a, b) => b.startDate.compareTo(a.startDate));

                return SliverList(
                  delegate: SliverChildListDelegate([
                    if (upcomingEvents.isNotEmpty) ...[
                      _buildSectionHeader("À VENIR"),
                      ...upcomingEvents.map((e) => _buildPremiumEventCard(e)),
                    ],
                    if (pastEvents.isNotEmpty) ...[
                      const SizedBox(height: 30),
                      _buildSectionHeader("PASSÉS"),
                      ...pastEvents.map((e) => _buildPremiumEventCard(e, isPast: true)),
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

  Widget _buildPremiumEventCard(Event event, {bool isPast = false}) {
    final dateStr = DateFormat('EEEE dd MMMM • HH:mm', 'fr_FR').format(event.startDate);
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
              builder: (context) => RepertoireScreen(
                eventId: event.id,
                title: event.title,
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
                    if (event.typeName != null)
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                        decoration: BoxDecoration(
                          color: const Color(0xFF7367F0).withAlpha(15),
                          borderRadius: BorderRadius.circular(10),
                        ),
                        child: Text(
                          event.typeName!.toUpperCase(),
                          style: GoogleFonts.outfit(
                            color: const Color(0xFF7367F0),
                            fontSize: 10,
                            fontWeight: FontWeight.bold,
                            letterSpacing: 1.1,
                          ),
                        ),
                      )
                    else if (isPast)
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                        decoration: BoxDecoration(
                          color: accentColor.withAlpha(15),
                          borderRadius: BorderRadius.circular(10),
                        ),
                        child: Text(
                          "PASSÉ",
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
                  event.title,
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
                      child: Icon(Icons.calendar_today_rounded, size: 16, color: accentColor),
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
                if (event.location != null) ...[
                  const SizedBox(height: 10),
                  Row(
                    children: [
                      Container(
                        padding: const EdgeInsets.all(8),
                        decoration: BoxDecoration(color: const Color(0xFFF8F7FA), borderRadius: BorderRadius.circular(10)),
                        child: const Icon(Icons.location_on_rounded, size: 16, color: Color(0xFFEA5455)),
                      ),
                      const SizedBox(width: 12),
                      Expanded(
                        child: Text(
                          event.location!,
                          style: GoogleFonts.outfit(color: Colors.blueGrey[400], fontSize: 13, fontWeight: FontWeight.w500),
                        ),
                      ),
                    ],
                  ),
                ],
                if (!isPast) ...[
                  const SizedBox(height: 15),
                  const Divider(height: 1),
                  const SizedBox(height: 15),
                  Text(
                    "Seriez-vous présent ?",
                    style: GoogleFonts.outfit(fontSize: 12, fontWeight: FontWeight.bold, color: Colors.blueGrey.shade600),
                  ),
                  const SizedBox(height: 10),
                  Row(
                    children: [
                      _buildPresenceChip(event, "Oui", "oui", Colors.green),
                      const SizedBox(width: 8),
                      _buildPresenceChip(event, "Non", "non", Colors.red),
                      const SizedBox(width: 8),
                      _buildPresenceChip(event, "Peut-être", "peut-etre", Colors.orange),
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
                      "Voir le répertoire",
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

  Widget _buildPresenceChip(Event event, String label, String value, Color color) {
    final bool isSelected = event.userChoice == value;
    
    return GestureDetector(
      onTap: () async {
        if (isSelected) return;
        
        try {
          // Optimistic UI update
          final Map<String, dynamic> eventJson = event.toJson();
          // Adjust for model field name vs JSON field name if necessary
          // Note: event.toJson uses 'start_date' but Event.fromJson uses 'start_at'
          // Let's just create a new Event object with the updated choice
          final updatedEvent = Event(
            id: event.id,
            title: event.title,
            startDate: event.startDate,
            location: event.location,
            description: event.description,
            userChoice: value,
          );
          
          setState(() {
            // We need to update the event in the list if we want to see the change
            // Since we're using a FutureBuilder, we might need a local copy of the list
            // For now, let's just trigger the service call
          });
          
          await _eventService.updateSondage(event.id, value);
          
          // Refresh the list to get new data and confirm state
          setState(() {
            _eventsFuture = _eventService.fetchEvents();
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
