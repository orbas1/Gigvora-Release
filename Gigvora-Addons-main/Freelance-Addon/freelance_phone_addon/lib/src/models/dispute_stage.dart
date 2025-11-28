class DisputeStage {
  const DisputeStage({
    required this.stage,
    required this.notes,
    required this.decision,
  });

  final String stage;
  final String? notes;
  final String? decision;

  factory DisputeStage.fromJson(Map<String, dynamic>? json) {
    final data = json ?? {};
    return DisputeStage(
      stage: data['stage']?.toString() ?? 'initial',
      notes: data['notes']?.toString(),
      decision: data['decision']?.toString(),
    );
  }
}
