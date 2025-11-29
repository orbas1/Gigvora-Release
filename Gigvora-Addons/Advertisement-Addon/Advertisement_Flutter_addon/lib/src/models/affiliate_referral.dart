import 'package:equatable/equatable.dart';

class AffiliateReferral extends Equatable {
  const AffiliateReferral({
    required this.id,
    required this.referrerId,
    required this.referredEmail,
    required this.status,
    required this.commission,
  });

  factory AffiliateReferral.fromJson(Map<String, dynamic> json) {
    return AffiliateReferral(
      id: json['id'] as int,
      referrerId: json['referrer_id'] as int,
      referredEmail: json['referred_email'] as String,
      status: json['status'] as String,
      commission: (json['commission'] as num).toDouble(),
    );
  }

  final int id;
  final int referrerId;
  final String referredEmail;
  final String status;
  final double commission;

  Map<String, dynamic> toJson() => {
        'id': id,
        'referrer_id': referrerId,
        'referred_email': referredEmail,
        'status': status,
        'commission': commission,
      };

  @override
  List<Object?> get props => [id, referrerId, referredEmail, status, commission];
}
