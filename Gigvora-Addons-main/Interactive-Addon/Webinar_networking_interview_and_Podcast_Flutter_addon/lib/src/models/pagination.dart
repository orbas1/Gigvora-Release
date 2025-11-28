class PaginatedResponse<T> {
  final List<T> data;
  final int currentPage;
  final int lastPage;
  final int perPage;
  final int total;

  const PaginatedResponse({
    required this.data,
    required this.currentPage,
    required this.lastPage,
    required this.perPage,
    required this.total,
  });

  factory PaginatedResponse.fromJson(
    Map<String, dynamic> json,
    T Function(Object? json) fromJson,
  ) {
    final items = (json['data'] as List<dynamic>? ?? [])
        .map((item) => fromJson(item))
        .toList();

    return PaginatedResponse(
      data: items,
      currentPage: json['current_page'] as int? ?? 1,
      lastPage: json['last_page'] as int? ?? 1,
      perPage: json['per_page'] as int? ?? items.length,
      total: json['total'] as int? ?? items.length,
    );
  }
}
