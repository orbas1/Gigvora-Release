class NewsletterSubscription {
  final String email;
  final bool subscribed;
  final DateTime? updatedAt;

  const NewsletterSubscription({
    required this.email,
    required this.subscribed,
    this.updatedAt,
  });

  factory NewsletterSubscription.fromJson(Map<String, dynamic> json) {
    return NewsletterSubscription(
      email: json['email'] as String? ?? '',
      subscribed: json['subscribed'] as bool? ?? true,
      updatedAt: json['updated_at'] != null
          ? DateTime.tryParse(json['updated_at'].toString())
          : null,
    );
  }

  Map<String, dynamic> toJson() => {
        'email': email,
        'subscribed': subscribed,
        if (updatedAt != null) 'updated_at': updatedAt!.toIso8601String(),
      };
}
