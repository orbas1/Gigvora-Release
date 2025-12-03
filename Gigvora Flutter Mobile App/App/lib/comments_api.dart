import 'dart:convert';

import 'package:flutter/foundation.dart';
import 'package:http/http.dart' as http;

@immutable
class GigvoraReactionCounts {
  final int like;
  final int love;
  final int sad;
  final int haha;
  final int angry;
  final int total;

  const GigvoraReactionCounts({
    required this.like,
    required this.love,
    required this.sad,
    required this.haha,
    required this.angry,
    required this.total,
  });

  factory GigvoraReactionCounts.fromJson(Map<String, dynamic> json) {
    return GigvoraReactionCounts(
      like: json['like'] as int? ?? 0,
      love: json['love'] as int? ?? 0,
      sad: json['sad'] as int? ?? 0,
      haha: json['haha'] as int? ?? 0,
      angry: json['angry'] as int? ?? 0,
      total: json['total'] as int? ?? 0,
    );
  }

  GigvoraReactionCounts copyWith({
    int? like,
    int? love,
    int? sad,
    int? haha,
    int? angry,
    int? total,
  }) {
    return GigvoraReactionCounts(
      like: like ?? this.like,
      love: love ?? this.love,
      sad: sad ?? this.sad,
      haha: haha ?? this.haha,
      angry: angry ?? this.angry,
      total: total ?? this.total,
    );
  }
}

@immutable
class GigvoraCommentUser {
  final int id;
  final String name;
  final String? photo;

  const GigvoraCommentUser({
    required this.id,
    required this.name,
    this.photo,
  });
}

@immutable
class GigvoraComment {
  final int id;
  final int postId;
  final String postType;
  final GigvoraCommentUser user;
  final String description;
  final String created;
  final String? userReaction;
  final GigvoraReactionCounts reactionCounts;
  final List<GigvoraComment> replies;

  const GigvoraComment({
    required this.id,
    required this.postId,
    required this.postType,
    required this.user,
    required this.description,
    required this.created,
    required this.reactionCounts,
    this.userReaction,
    this.replies = const [],
  });

  factory GigvoraComment.fromJson(Map<String, dynamic> json) {
    final id = json['comment_id'] ?? json['reply_id'];
    final reactions = GigvoraReactionCounts.fromJson(
      (json['reaction_counts'] as Map<String, dynamic>? ?? const {}),
    );

    return GigvoraComment(
      id: id is int ? id : int.tryParse('$id') ?? 0,
      postId: json['post_id'] as int? ?? 0,
      postType: json['post_type'] as String? ?? 'post',
      description: json['description'] as String? ?? '',
      created: json['created'] as String? ?? '',
      userReaction: json['userReaction'] as String?,
      reactionCounts: reactions,
      user: GigvoraCommentUser(
        id: json['user_id'] as int? ?? 0,
        name: json['name'] as String? ?? '',
        photo: json['photo'] as String?,
      ),
      replies: (json['replies'] as List<dynamic>? ?? const [])
          .map((reply) => GigvoraComment.fromJson(reply as Map<String, dynamic>))
          .toList(),
    );
  }
}

@immutable
class GigvoraCommentAck {
  final bool success;
  final String message;

  const GigvoraCommentAck({
    required this.success,
    required this.message,
  });
}

class GigvoraCommentsException implements Exception {
  final String message;
  final Object? cause;

  GigvoraCommentsException(this.message, [this.cause]);

  @override
  String toString() => 'GigvoraCommentsException($message)';
}

class GigvoraCommentsClient {
  final String baseUrl;
  final Future<String?> Function() tokenProvider;
  final http.Client _http;

  GigvoraCommentsClient({
    required this.baseUrl,
    required this.tokenProvider,
    http.Client? httpClient,
  }) : _http = httpClient ?? http.Client();

  Uri _api(String path, [Map<String, String>? query]) {
    final normalizedBase = baseUrl.endsWith('/')
        ? baseUrl.substring(0, baseUrl.length - 1)
        : baseUrl;
    final normalizedPath = path.startsWith('/') ? path : '/$path';
    final uri = Uri.parse('$normalizedBase/api$normalizedPath');
    if (query == null || query.isEmpty) {
      return uri;
    }
    return uri.replace(queryParameters: {...uri.queryParameters, ...query});
  }

  Future<Map<String, String>> _headers() async {
    final token = await tokenProvider();
    return {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      if (token != null && token.isNotEmpty) 'Authorization': 'Bearer $token',
    };
  }

  Future<List<GigvoraComment>> fetchThread({
    required int postId,
    Duration timeout = const Duration(seconds: 15),
  }) async {
    final response = await _http
        .get(_api('/get_comment/$postId'), headers: await _headers())
        .timeout(timeout);

    if (response.statusCode >= 400) {
      throw GigvoraCommentsException(
        'Unable to load comments for post $postId (status ${response.statusCode})',
      );
    }

    final decoded = jsonDecode(response.body);
    if (decoded is! List) {
      throw GigvoraCommentsException('Unexpected payload when loading comments');
    }

    return decoded
        .map((item) => GigvoraComment.fromJson(item as Map<String, dynamic>))
        .toList();
  }

  Future<GigvoraCommentAck> createComment({
    required int postId,
    required String body,
    int parentId = 0,
    String postType = 'post',
    Duration timeout = const Duration(seconds: 15),
  }) async {
    final payload = {
      'comment': 'comment',
      'parent_id': parentId,
      'is_type': postType,
      'id_of_type': postId,
      'description': body,
    };

    final response = await _http
        .post(
          _api('/post_comment'),
          headers: await _headers(),
          body: jsonEncode(payload),
        )
        .timeout(timeout);

    if (response.statusCode >= 400) {
      throw GigvoraCommentsException(
        'Unable to publish comment (status ${response.statusCode})',
      );
    }

    final decoded = jsonDecode(response.body) as Map<String, dynamic>? ?? const {};
    final message = decoded['message'] as String? ?? 'Comment posted';
    final status = decoded['status'] as int? ?? response.statusCode;

    return GigvoraCommentAck(
      success: status >= 200 && status < 300,
      message: message,
    );
  }

  Future<GigvoraReactionCounts> reactToComment({
    required int commentId,
    required String reaction,
    Duration timeout = const Duration(seconds: 15),
  }) async {
    final payload = {
      'comment': 'reaction',
      'comment_id': commentId,
      'react': reaction,
    };

    final response = await _http
        .post(
          _api('/post_comment'),
          headers: await _headers(),
          body: jsonEncode(payload),
        )
        .timeout(timeout);

    if (response.statusCode >= 400) {
      throw GigvoraCommentsException(
        'Unable to react to comment $commentId (status ${response.statusCode})',
      );
    }

    final decoded = jsonDecode(response.body);
    if (decoded is! Map<String, dynamic>) {
      return const GigvoraReactionCounts(
        like: 0,
        love: 0,
        sad: 0,
        haha: 0,
        angry: 0,
        total: 0,
      );
    }

    return _calculateReactionCounts(decoded.values);
  }

  Future<GigvoraCommentAck> deleteComment({
    required int commentId,
    Duration timeout = const Duration(seconds: 15),
  }) async {
    final response = await _http
        .post(
          _api('/comment_delete/$commentId'),
          headers: await _headers(),
        )
        .timeout(timeout);

    if (response.statusCode >= 400) {
      throw GigvoraCommentsException(
        'Unable to delete comment $commentId (status ${response.statusCode})',
      );
    }

    final decoded = jsonDecode(response.body) as Map<String, dynamic>? ?? const {};
    final message = decoded['message'] as String? ?? 'Comment deleted';

    return GigvoraCommentAck(
      success: response.statusCode >= 200 && response.statusCode < 300,
      message: message,
    );
  }

  GigvoraReactionCounts _calculateReactionCounts(Iterable<dynamic> reactions) {
    var like = 0;
    var love = 0;
    var sad = 0;
    var haha = 0;
    var angry = 0;

    for (final react in reactions) {
      switch ('$react') {
        case 'like':
          like++;
          break;
        case 'love':
          love++;
          break;
        case 'sad':
          sad++;
          break;
        case 'haha':
          haha++;
          break;
        case 'angry':
          angry++;
          break;
        default:
          break;
      }
    }

    final total = like + love + sad + haha + angry;

    return GigvoraReactionCounts(
      like: like,
      love: love,
      sad: sad,
      haha: haha,
      angry: angry,
      total: total,
    );
  }
}

