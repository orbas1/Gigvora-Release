class CvDocument {
  const CvDocument({
    this.id,
    required this.candidateId,
    required this.title,
    required this.content,
    this.isDefault = false,
  });

  final int? id;
  final int candidateId;
  final String title;
  final Map<String, dynamic> content;
  final bool isDefault;

  factory CvDocument.fromJson(Map<String, dynamic> json) => CvDocument(
        id: json['id'] as int?,
        candidateId: json['candidate_id'] as int,
        title: json['title'] as String,
        content: (json['content'] as Map<String, dynamic>? ?? <String, dynamic>{}),
        isDefault: json['is_default'] as bool? ?? false,
      );

  Map<String, dynamic> toJson() => {
        if (id != null) 'id': id,
        'candidate_id': candidateId,
        'title': title,
        'content': content,
        'is_default': isDefault,
      };
}
