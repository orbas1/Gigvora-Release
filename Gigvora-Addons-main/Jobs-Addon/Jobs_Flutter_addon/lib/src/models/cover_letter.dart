class CoverLetter {
  const CoverLetter({
    this.id,
    required this.candidateId,
    required this.title,
    required this.body,
  });

  final int? id;
  final int candidateId;
  final String title;
  final String body;

  factory CoverLetter.fromJson(Map<String, dynamic> json) => CoverLetter(
        id: json['id'] as int?,
        candidateId: json['candidate_id'] as int,
        title: json['title'] as String,
        body: json['body'] as String? ?? '',
      );

  Map<String, dynamic> toJson() => {
        if (id != null) 'id': id,
        'candidate_id': candidateId,
        'title': title,
        'body': body,
      };
}
