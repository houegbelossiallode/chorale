class Event {
  final String id;
  final String title;
  final DateTime startDate;
  final String? location;
  final String? description;

  Event({
    required this.id,
    required this.title,
    required this.startDate,
    this.location,
    this.description,
  });

  factory Event.fromJson(Map<String, dynamic> json) {
    return Event(
      id: json['id'].toString(),
      title: json['title'] ?? 'Événement sans titre',
      startDate: DateTime.parse(json['start_at']),
      location: json['location'],
      description: json['description'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'title': title,
      'start_date': startDate.toIso8601String(),
      'location': location,
      'description': description,
    };
  }
}
