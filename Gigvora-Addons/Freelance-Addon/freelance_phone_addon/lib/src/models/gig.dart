class Gig {
  Gig({
    required this.id,
    required this.title,
    required this.status,
    required this.price,
    required this.rating,
    required this.ordersQueue,
  });

  final int id;
  final String title;
  final String status;
  final double price;
  final double rating;
  final int ordersQueue;

  factory Gig.fromJson(Map<String, dynamic> json) => Gig(
        id: json['id'] as int,
        title: json['title'] as String,
        status: json['status'] as String? ?? 'draft',
        price: (json['price'] as num?)?.toDouble() ?? 0,
        rating: (json['rating'] as num?)?.toDouble() ?? 0,
        ordersQueue: json['orders_queue'] as int? ?? 0,
      );

  Map<String, dynamic> toJson() => {
        'id': id,
        'title': title,
        'status': status,
        'price': price,
        'rating': rating,
        'orders_queue': ordersQueue,
      };
}
