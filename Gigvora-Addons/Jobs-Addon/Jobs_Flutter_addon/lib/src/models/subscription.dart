class SubscriptionPlan {
  const SubscriptionPlan({
    required this.id,
    required this.name,
    required this.price,
    required this.currency,
    required this.jobLimit,
  });

  final int id;
  final String name;
  final double price;
  final String currency;
  final int jobLimit;

  factory SubscriptionPlan.fromJson(Map<String, dynamic> json) => SubscriptionPlan(
        id: json['id'] as int,
        name: json['name'] as String,
        price: (json['price'] as num).toDouble(),
        currency: json['currency'] as String? ?? 'USD',
        jobLimit: json['job_limit'] as int? ?? 0,
      );

  Map<String, dynamic> toJson() => {
        'id': id,
        'name': name,
        'price': price,
        'currency': currency,
        'job_limit': jobLimit,
      };
}

class SubscriptionStatus {
  const SubscriptionStatus({required this.plan, required this.expiresAt});

  final SubscriptionPlan plan;
  final DateTime expiresAt;

  factory SubscriptionStatus.fromJson(Map<String, dynamic> json) =>
      SubscriptionStatus(
        plan: SubscriptionPlan.fromJson(json['plan'] as Map<String, dynamic>),
        expiresAt: DateTime.parse(json['expires_at'] as String),
      );

  Map<String, dynamic> toJson() => {
        'plan': plan.toJson(),
        'expires_at': expiresAt.toIso8601String(),
      };
}
