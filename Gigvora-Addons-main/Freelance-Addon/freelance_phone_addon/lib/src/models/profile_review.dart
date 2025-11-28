import 'package:equatable/equatable.dart';

class ProfileReview extends Equatable {
  const ProfileReview({
    required this.id,
    required this.userId,
    required this.rating,
    this.reviewer,
    this.comment,
    this.reference,
    this.createdAt,
  });

  final int id;
  final int userId;
  final double rating;
  final String? reviewer;
  final String? comment;
  final String? reference;
  final DateTime? createdAt;

  factory ProfileReview.fromJson(Map<String, dynamic>? json) {
    if (json == null) {
      return const ProfileReview(id: 0, userId: 0, rating: 0);
    }

    DateTime? _parse(dynamic value) => value == null ? null : DateTime.tryParse(value.toString());

    return ProfileReview(
      id: json['id'] is int ? json['id'] : int.tryParse(json['id']?.toString() ?? '') ?? 0,
      userId: json['user_id'] is int ? json['user_id'] : int.tryParse(json['user_id']?.toString() ?? '') ?? 0,
      rating: json['rating'] is num
          ? (json['rating'] as num).toDouble()
          : double.tryParse(json['rating']?.toString() ?? '') ?? 0,
      reviewer: json['reviewer']?.toString(),
      comment: json['comment']?.toString(),
      reference: json['reference']?.toString(),
      createdAt: _parse(json['created_at']),
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'user_id': userId,
        'rating': rating,
        'reviewer': reviewer,
        'comment': comment,
        'reference': reference,
        'created_at': createdAt?.toIso8601String(),
      };

  @override
  List<Object?> get props => [id, userId, rating, reviewer, comment, reference, createdAt];
}
