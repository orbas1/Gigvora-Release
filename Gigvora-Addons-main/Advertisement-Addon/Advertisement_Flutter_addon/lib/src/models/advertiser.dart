import 'package:equatable/equatable.dart';

class Advertiser extends Equatable {
  const Advertiser({
    required this.id,
    required this.name,
    required this.contactEmail,
    required this.status,
    this.dailySpendLimit,
  });

  factory Advertiser.fromJson(Map<String, dynamic> json) {
    return Advertiser(
      id: json['id'] as int,
      name: json['name'] as String,
      contactEmail: json['contact_email'] as String,
      status: json['status'] as String,
      dailySpendLimit: (json['daily_spend_limit'] as num?)?.toDouble(),
    );
  }

  final int id;
  final String name;
  final String contactEmail;
  final String status;
  final double? dailySpendLimit;

  Map<String, dynamic> toJson() => {
        'id': id,
        'name': name,
        'contact_email': contactEmail,
        'status': status,
        'daily_spend_limit': dailySpendLimit,
      };

  @override
  List<Object?> get props => [id, name, contactEmail, status, dailySpendLimit];
}
