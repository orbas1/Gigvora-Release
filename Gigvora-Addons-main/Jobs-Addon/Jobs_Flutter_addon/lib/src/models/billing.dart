class BillingPlan {
  const BillingPlan({
    required this.id,
    required this.name,
    required this.price,
    required this.jobsIncluded,
    required this.durationDays,
    this.creditsRemaining,
    this.description,
    this.active = true,
  });

  final int id;
  final String name;
  final double price;
  final int jobsIncluded;
  final int durationDays;
  final int? creditsRemaining;
  final String? description;
  final bool active;

  factory BillingPlan.fromJson(Map<String, dynamic> json) => BillingPlan(
        id: json['id'] as int,
        name: json['name'] as String,
        price: (json['price'] as num).toDouble(),
        jobsIncluded: json['jobs_included'] as int? ?? json['jobs_count'] as int? ?? 0,
        durationDays: json['duration_days'] as int? ?? json['duration'] as int? ?? 0,
        creditsRemaining: json['credits_remaining'] as int?,
        description: json['description'] as String?,
        active: json['active'] as bool? ?? true,
      );

  Map<String, dynamic> toJson() => {
        'id': id,
        'name': name,
        'price': price,
        'jobs_included': jobsIncluded,
        'duration_days': durationDays,
        'credits_remaining': creditsRemaining,
        'description': description,
        'active': active,
      };
}

class InvoiceSummary {
  const InvoiceSummary({
    required this.id,
    required this.date,
    required this.description,
    required this.amount,
    required this.status,
  });

  final int id;
  final DateTime date;
  final String description;
  final double amount;
  final String status;

  factory InvoiceSummary.fromJson(Map<String, dynamic> json) => InvoiceSummary(
        id: json['id'] as int,
        date: DateTime.parse(json['date'] as String),
        description: json['description'] as String? ?? '',
        amount: (json['amount'] as num).toDouble(),
        status: json['status'] as String? ?? 'paid',
      );

  Map<String, dynamic> toJson() => {
        'id': id,
        'date': date.toIso8601String(),
        'description': description,
        'amount': amount,
        'status': status,
      };
}
