class GigPackage {
  const GigPackage({
    required this.id,
    required this.name,
    required this.price,
    required this.deliveryTime,
  });

  final int id;
  final String name;
  final double price;
  final int deliveryTime;

  factory GigPackage.fromJson(Map<String, dynamic>? json) {
    final data = json ?? {};
    return GigPackage(
      id: data['id'] is int ? data['id'] as int : int.tryParse('${data['id'] ?? 0}') ?? 0,
      name: data['name']?.toString() ?? '',
      price: (data['price'] as num?)?.toDouble() ?? 0,
      deliveryTime: int.tryParse('${data['delivery_time'] ?? 0}') ?? 0,
    );
  }
}
