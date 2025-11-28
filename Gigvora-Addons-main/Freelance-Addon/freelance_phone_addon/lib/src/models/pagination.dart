import 'package:equatable/equatable.dart';

class Pagination extends Equatable {
  const Pagination({
    required this.total,
    required this.currentPage,
    required this.perPage,
  });

  final int total;
  final int currentPage;
  final int perPage;

  factory Pagination.fromJson(Map<String, dynamic>? json) {
    if (json == null) {
      return const Pagination(total: 0, currentPage: 1, perPage: 10);
    }
    return Pagination(
      total: json['total'] is int ? json['total'] : int.tryParse(json['total'].toString()) ?? 0,
      currentPage: json['current_page'] is int
          ? json['current_page']
          : int.tryParse(json['current_page'].toString()) ?? 1,
      perPage:
          json['per_page'] is int ? json['per_page'] : int.tryParse(json['per_page'].toString()) ?? 10,
    );
  }

  Map<String, dynamic> toJson() => {
        'total': total,
        'current_page': currentPage,
        'per_page': perPage,
      };

  @override
  List<Object?> get props => [total, currentPage, perPage];
}

class PagedResult<T> extends Equatable {
  const PagedResult({
    required this.items,
    required this.pagination,
  });

  final List<T> items;
  final Pagination pagination;

  @override
  List<Object?> get props => [items, pagination];
}
