class Repetition {
  final String id;
  final String? title;
  final DateTime date;
  final String? location;
  final String? userChoice;

  Repetition({
    required this.id,
    this.title,
    required this.date,
    this.location,
    this.userChoice,
  });

  factory Repetition.fromJson(Map<String, dynamic> json) {
    return Repetition(
      id: json['id'].toString(),
      title: json['titre'] ?? json['title'],
      date: DateTime.parse(json['start_time']),
      location: json['lieu'] ?? json['location'],
      userChoice: json['user_choice'],
    );
  }
}
