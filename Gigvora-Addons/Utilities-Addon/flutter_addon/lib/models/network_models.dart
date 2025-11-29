class NetworkConnection {
  final int id;
  final int userId;
  final int degree;
  final DateTime? connectedAt;
  final String? name;
  final String? title;
  final int? mutualCount;
  final Map<String, dynamic> metadata;

  const NetworkConnection({
    required this.id,
    required this.userId,
    this.degree = 1,
    this.connectedAt,
    this.name,
    this.title,
    this.mutualCount,
    this.metadata = const {},
  });

  factory NetworkConnection.fromJson(Map<String, dynamic> json) {
    return NetworkConnection(
      id: json['connection_id'] as int? ?? json['id'] as int,
      userId: json['user_id'] as int? ?? json['userId'] as int? ?? json['id'] as int,
      degree: json['degree'] as int? ?? json['degree_level'] as int? ?? 1,
      name: json['name'] as String? ?? json['participant'] as String?,
      title: json['title'] as String? ?? json['headline'] as String?,
      mutualCount: json['mutual_count'] as int?,
      connectedAt: json['connected_at'] != null
          ? DateTime.tryParse(json['connected_at'].toString())
          : json['last_message_at'] != null
              ? DateTime.tryParse(json['last_message_at'].toString())
              : null,
      metadata: Map<String, dynamic>.from(json['metadata'] as Map? ?? {}),
    );
  }

  Map<String, dynamic> toJson() => {
        'connection_id': id,
        'user_id': userId,
        'degree': degree,
        if (name != null) 'name': name,
        if (title != null) 'title': title,
        if (mutualCount != null) 'mutual_count': mutualCount,
        'connected_at': connectedAt?.toIso8601String(),
        if (metadata.isNotEmpty) 'metadata': metadata,
      };
}

class NetworkSummary {
  final int firstDegree;
  final int secondDegree;
  final int thirdDegree;
  final int totalConnections;
  final Map<String, dynamic>? quickStats;

  const NetworkSummary({
    this.firstDegree = 0,
    this.secondDegree = 0,
    this.thirdDegree = 0,
    this.totalConnections = 0,
    this.quickStats,
  });

  factory NetworkSummary.fromJson(Map<String, dynamic> json) {
    return NetworkSummary(
      firstDegree: json['first_degree'] as int? ?? json['firstDegree'] as int? ?? 0,
      secondDegree: json['second_degree'] as int? ?? json['secondDegree'] as int? ?? 0,
      thirdDegree: json['third_degree'] as int? ?? json['thirdDegree'] as int? ?? 0,
      totalConnections: json['total_connections'] as int? ??
          json['totalConnections'] as int? ??
          json['total'] as int? ??
          0,
      quickStats: (json['quick_stats'] as Map<String, dynamic>?) ??
          (json['quickStats'] as Map<String, dynamic>?),
    );
  }

  Map<String, dynamic> toJson() => {
        'first_degree': firstDegree,
        'second_degree': secondDegree,
        'third_degree': thirdDegree,
        'total_connections': totalConnections,
        if (quickStats != null) 'quick_stats': quickStats,
      };
}

class PaginationMeta {
  final int currentPage;
  final int perPage;
  final int total;

  const PaginationMeta({
    required this.currentPage,
    required this.perPage,
    required this.total,
  });

  factory PaginationMeta.fromJson(Map<String, dynamic> json) {
    return PaginationMeta(
      currentPage: json['current_page'] as int? ?? 1,
      perPage: json['per_page'] as int? ?? 15,
      total: json['total'] as int? ?? 0,
    );
  }

  Map<String, dynamic> toJson() => {
        'current_page': currentPage,
        'per_page': perPage,
        'total': total,
      };
}

class PaginatedConnections {
  final List<NetworkConnection> data;
  final PaginationMeta meta;
  final NetworkSummary summary;

  const PaginatedConnections({
    required this.data,
    required this.meta,
    required this.summary,
  });

  factory PaginatedConnections.fromJson(Map<String, dynamic> json) {
    final items = (json['data'] as List? ?? json['connections'] as List? ?? [])
        .map((e) => NetworkConnection.fromJson(e as Map<String, dynamic>))
        .toList();
    final meta = PaginationMeta.fromJson(
        (json['meta'] as Map<String, dynamic>?) ?? {'current_page': 1, 'per_page': items.length, 'total': items.length});
    final summary = NetworkSummary(
      totalConnections: meta.total,
      firstDegree: items.where((e) => e.degree == 1).length,
      secondDegree: items.where((e) => e.degree == 2).length,
      thirdDegree: items.where((e) => e.degree == 3).length,
    );

    return PaginatedConnections(data: items, meta: meta, summary: summary);
  }
}

class RecommendationItem {
  final String type;
  final int? id;
  final String title;
  final String? subtitle;
  final String? imageUrl;
  final Map<String, dynamic>? metadata;

  const RecommendationItem({
    required this.type,
    this.id,
    required this.title,
    this.subtitle,
    this.imageUrl,
    this.metadata,
  });

  factory RecommendationItem.fromJson(Map<String, dynamic> json) {
    return RecommendationItem(
      type: json['type'] as String? ?? 'generic',
      id: json['id'] as int?,
      title: json['title'] as String? ?? json['name'] as String? ?? '',
      subtitle: json['subtitle'] as String?,
      imageUrl: json['image_url'] as String? ?? json['imageUrl'] as String?,
      metadata: (json['metadata'] as Map<String, dynamic>?) ?? {},
    );
  }

  Map<String, dynamic> toJson() => {
        'type': type,
        if (id != null) 'id': id,
        'title': title,
        if (subtitle != null) 'subtitle': subtitle,
        if (imageUrl != null) 'image_url': imageUrl,
        if (metadata != null) 'metadata': metadata,
      };
}

class RecommendationResult {
  final String type;
  final List<RecommendationItem> items;

  const RecommendationResult({required this.type, this.items = const []});

  factory RecommendationResult.fromJson(Map<String, dynamic> json) {
    return RecommendationResult(
      type: json['type'] as String? ?? 'generic',
      items: (json['items'] as List? ?? [])
          .map((e) => RecommendationItem.fromJson(e as Map<String, dynamic>))
          .toList(),
    );
  }
}
