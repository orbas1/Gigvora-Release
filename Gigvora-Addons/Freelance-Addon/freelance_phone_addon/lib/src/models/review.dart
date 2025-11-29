class Review {
  Review({required this.rating, required this.headline, required this.comment});
  final int rating;
  final String headline;
  final String comment;

  factory Review.fromJson(Map<String, dynamic> json) => Review(
        rating: json['rating'] as int? ?? 0,
        headline: json['headline'] as String? ?? '',
        comment: json['comment'] as String? ?? '',
      );

  Map<String, dynamic> toJson() => {
        'rating': rating,
        'headline': headline,
        'comment': comment,
      };
}
