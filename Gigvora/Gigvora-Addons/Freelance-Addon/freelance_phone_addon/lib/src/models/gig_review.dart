class GigReview {
  const GigReview({
    required this.id,
    required this.rating,
    required this.comment,
    required this.author,
  });

  final int id;
  final double rating;
  final String? comment;
  final String author;

  factory GigReview.fromJson(Map<String, dynamic>? json) {
    final data = json ?? {};
    return GigReview(
      id: data['id'] is int ? data['id'] as int : int.tryParse('${data['id'] ?? 0}') ?? 0,
      rating: (data['rating'] as num?)?.toDouble() ?? 0,
      comment: data['comment']?.toString(),
      author: data['author']?.toString() ?? '',
    );
  }
}
