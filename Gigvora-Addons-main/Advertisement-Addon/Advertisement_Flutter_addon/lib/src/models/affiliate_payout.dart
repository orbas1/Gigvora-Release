import 'package:equatable/equatable.dart';

class AffiliatePayout extends Equatable {
  const AffiliatePayout({
    required this.id,
    required this.referrerId,
    required this.amount,
    required this.status,
    required this.payoutDate,
  });

  factory AffiliatePayout.fromJson(Map<String, dynamic> json) {
    return AffiliatePayout(
      id: json['id'] as int,
      referrerId: json['referrer_id'] as int,
      amount: (json['amount'] as num).toDouble(),
      status: json['status'] as String,
      payoutDate: DateTime.parse(json['payout_date'] as String),
    );
  }

  final int id;
  final int referrerId;
  final double amount;
  final String status;
  final DateTime payoutDate;

  Map<String, dynamic> toJson() => {
        'id': id,
        'referrer_id': referrerId,
        'amount': amount,
        'status': status,
        'payout_date': payoutDate.toIso8601String(),
      };

  @override
  List<Object?> get props => [id, referrerId, amount, status, payoutDate];
}
