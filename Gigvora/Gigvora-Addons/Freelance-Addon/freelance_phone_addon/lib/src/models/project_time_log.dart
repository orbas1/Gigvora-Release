class ProjectTimeLog {
  const ProjectTimeLog({
    required this.id,
    required this.freelancer,
    required this.hours,
    required this.note,
    required this.loggedAt,
  });

  final int id;
  final String freelancer;
  final double hours;
  final String? note;
  final DateTime? loggedAt;

  factory ProjectTimeLog.fromJson(Map<String, dynamic>? json) {
    final data = json ?? {};
    return ProjectTimeLog(
      id: data['id'] is int ? data['id'] as int : int.tryParse('${data['id'] ?? 0}') ?? 0,
      freelancer: data['freelancer']?.toString() ?? '',
      hours: (data['hours'] as num?)?.toDouble() ?? 0,
      note: data['note']?.toString(),
      loggedAt: data['logged_at'] != null ? DateTime.tryParse(data['logged_at'].toString()) : null,
    );
  }
}
