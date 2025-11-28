class CandidateProfile {
  const CandidateProfile({
    required this.id,
    required this.name,
    required this.title,
    required this.location,
    this.summary,
    this.cvUrl,
    this.stage,
  });

  final int id;
  final String name;
  final String title;
  final String location;
  final String? summary;
  final String? cvUrl;
  final String? stage;

  factory CandidateProfile.fromJson(Map<String, dynamic> json) => CandidateProfile(
        id: json['id'] as int,
        name: json['name'] as String,
        title: json['title'] as String? ?? 'Candidate',
        location: json['location'] as String? ?? 'Remote',
        summary: json['summary'] as String?,
        cvUrl: json['cv_url'] as String?,
        stage: json['stage'] as String?,
      );

  Map<String, dynamic> toJson() => {
        'id': id,
        'name': name,
        'title': title,
        'location': location,
        'summary': summary,
        'cv_url': cvUrl,
        'stage': stage,
      };
}
