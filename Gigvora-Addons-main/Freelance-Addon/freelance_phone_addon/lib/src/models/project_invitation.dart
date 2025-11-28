class ProjectInvitation {
  const ProjectInvitation({
    required this.id,
    required this.freelancer,
    required this.status,
    required this.message,
  });

  final int id;
  final String freelancer;
  final String status;
  final String? message;

  factory ProjectInvitation.fromJson(Map<String, dynamic>? json) {
    final data = json ?? {};
    return ProjectInvitation(
      id: data['id'] is int ? data['id'] as int : int.tryParse('${data['id'] ?? 0}') ?? 0,
      freelancer: data['freelancer']?.toString() ?? '',
      status: data['status']?.toString() ?? 'pending',
      message: data['message']?.toString(),
    );
  }
}
