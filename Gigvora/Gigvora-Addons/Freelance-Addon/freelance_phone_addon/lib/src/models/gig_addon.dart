class GigAddon {
  const GigAddon({
    required this.id,
    required this.title,
    required this.price,
  });

  final int id;
  final String title;
  final double price;

  factory GigAddon.fromJson(Map<String, dynamic>? json) {
    final data = json ?? {};
    return GigAddon(
      id: data['id'] is int ? data['id'] as int : int.tryParse('${data['id'] ?? 0}') ?? 0,
      title: data['title']?.toString() ?? '',
      price: (data['price'] as num?)?.toDouble() ?? 0,
    );
  }
}
