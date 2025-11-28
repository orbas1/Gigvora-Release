class EscrowAction {
  const EscrowAction({
    required this.id,
    required this.type,
    required this.amount,
    required this.actor,
    required this.decision,
    required this.notes,
  });

  final int id;
  final String type;
  final double? amount;
  final String? actor;
  final String? decision;
  final String? notes;

  factory EscrowAction.fromJson(Map<String, dynamic>? json) {
    final data = json ?? {};
    return EscrowAction(
      id: data['id'] is int ? data['id'] as int : int.tryParse('${data['id'] ?? 0}') ?? 0,
      type: data['type']?.toString() ?? 'note',
      amount: (data['amount'] as num?)?.toDouble(),
      actor: data['actor']?.toString(),
      decision: data['decision']?.toString(),
      notes: data['notes']?.toString(),
    );
  }
}
