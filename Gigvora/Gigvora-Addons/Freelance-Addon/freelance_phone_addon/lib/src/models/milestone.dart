class Milestone {
  Milestone({
    required this.title,
    required this.amount,
    required this.dueDate,
    required this.status,
  });

  final String title;
  final double amount;
  final String dueDate;
  final String status;

  factory Milestone.fromJson(Map<String, dynamic> json) => Milestone(
        title: json['title'] as String,
        amount: (json['amount'] as num).toDouble(),
        dueDate: json['due_date'] as String? ?? '',
        status: json['status'] as String? ?? 'pending',
      );

  Map<String, dynamic> toJson() => {
        'title': title,
        'amount': amount,
        'due_date': dueDate,
        'status': status,
      };
}
