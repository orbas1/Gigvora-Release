import 'package:equatable/equatable.dart';

import 'user.dart';

class Bid extends Equatable {
  const Bid({
    required this.id,
    required this.amount,
    required this.currency,
    required this.coverLetter,
    this.status,
    this.createdAt,
    this.bidder,
  });

  final int id;
  final double amount;
  final String currency;
  final String coverLetter;
  final String? status;
  final String? createdAt;
  final UserProfile? bidder;

  factory Bid.fromJson(Map<String, dynamic>? json) {
    if (json == null) {
      return const Bid(id: 0, amount: 0, currency: '', coverLetter: '');
    }
    return Bid(
      id: json['id'] is int ? json['id'] : int.tryParse(json['id']?.toString() ?? '') ?? 0,
      amount: json['amount'] is num
          ? (json['amount'] as num).toDouble()
          : double.tryParse(json['amount']?.toString() ?? '') ?? 0,
      currency: json['currency']?.toString() ?? 'USD',
      coverLetter: json['cover_letter']?.toString() ?? json['comment']?.toString() ?? '',
      status: json['status']?.toString(),
      createdAt: json['created_at']?.toString(),
      bidder: UserProfile.fromJson(json['user'] as Map<String, dynamic>?),
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'amount': amount,
        'currency': currency,
        'cover_letter': coverLetter,
        'status': status,
        'created_at': createdAt,
        'user': bidder?.toJson(),
      };

  @override
  List<Object?> get props => [id, amount, currency, coverLetter, status, createdAt, bidder];
}
