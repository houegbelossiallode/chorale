class Chant {
  final String id;
  final String title;
  final String? composer;
  final String? parole;
  final String? filePath;

  Chant({
    required this.id,
    required this.title,
    this.composer,
    this.parole,
    this.filePath,
  });

  factory Chant.fromJson(Map<String, dynamic> json) {
    return Chant(
      id: json['id'].toString(),
      title: json['title'] ?? 'Sans titre',
      composer: json['composer'],
      parole: json['parole'],
      filePath: json['file_path'],
    );
  }
}
