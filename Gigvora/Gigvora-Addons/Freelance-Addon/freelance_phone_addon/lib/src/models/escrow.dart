class Escrow {
  Escrow({required this.id, required this.amount, required this.status, required this.title});
  final int id;
  final double amount;
  final String status;
  final String title;

  factory Escrow.fromJson(Map<String, dynamic> json) => Escrow(
        id: json['id'] as int,
        amount: (json['amount'] as num).toDouble(),
        status: json['status'] as String? ?? 'pending',
        title: json['title'] as String? ?? '',
      );

  Map<String, dynamic> toJson() => {
        'id': id,
        'amount': amount,
        'status': status,
        'title': title,
      };
}
